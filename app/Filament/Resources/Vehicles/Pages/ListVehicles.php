<?php

namespace App\Filament\Resources\Vehicles\Pages;

use App\Filament\Resources\Vehicles\VehicleResource;
use App\Models\Vehicle;
use App\Models\VehicleImage;
use App\Services\MobileDeParser;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use ZipArchive;

class ListVehicles extends ListRecords
{
    protected static string $resource = VehicleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),

            Action::make('mobileDeImport')
                ->label('mobile.de Import')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('warning')
                ->form([
                    FileUpload::make('zip_file')
                        ->label('mobile.de Export-ZIP')
                        ->acceptedFileTypes(['application/zip', 'application/x-zip-compressed'])
                        ->directory('mobile-imports')
                        ->visibility('private')
                        ->maxSize(102400)
                        ->required(),
                ])
                ->action(function (array $data) {
                    $zipPath = storage_path('app/private/' . $data['zip_file']);

                    if (! file_exists($zipPath)) {
                        Notification::make()->title('ZIP-Datei nicht gefunden')->danger()->send();
                        return;
                    }

                    $zip = new ZipArchive();
                    if ($zip->open($zipPath) !== true) {
                        Notification::make()->title('ZIP konnte nicht geöffnet werden')->danger()->send();
                        return;
                    }

                    $tmpDir = storage_path('app/private/mobile-import-tmp-' . time());
                    $zip->extractTo($tmpDir);
                    $zip->close();

                    $adsJsonPath = $tmpDir . '/ads.json';
                    if (! file_exists($adsJsonPath)) {
                        $this->cleanupDir($tmpDir);
                        Notification::make()->title('ads.json fehlt in der ZIP-Datei')->danger()->send();
                        return;
                    }

                    try {
                        $ads = MobileDeParser::parseAdsJson(file_get_contents($adsJsonPath));
                    } catch (\Throwable $e) {
                        $this->cleanupDir($tmpDir);
                        Notification::make()->title('Fehler beim Parsen')->body($e->getMessage())->danger()->send();
                        return;
                    }

                    $created = 0;
                    $updated = 0;
                    $imageCount = 0;

                    foreach ($ads as $ad) {
                        $imageUrls = $ad['images'] ?? [];
                        unset($ad['images']);

                        $mobileAdId = $ad['mobile_ad_id'];

                        $vehicle = $mobileAdId
                            ? Vehicle::where('mobile_ad_id', $mobileAdId)->first()
                            : null;

                        if ($vehicle) {
                            $vehicle->update($ad);
                            $updated++;
                        } else {
                            $vehicle = Vehicle::create($ad);
                            $created++;
                        }

                        if (! empty($imageUrls) && $vehicle->images()->count() === 0) {
                            $jpgFiles = glob($tmpDir . '/' . $mobileAdId . '_*.jpg');

                            if (! empty($jpgFiles)) {
                                sort($jpgFiles);
                                $disk = \Illuminate\Support\Facades\Storage::disk('public');

                                foreach ($jpgFiles as $i => $jpg) {
                                    $filename = 'vehicles/' . $vehicle->id . '_' . ($i + 1) . '.jpg';
                                    $disk->put($filename, file_get_contents($jpg));

                                    VehicleImage::create([
                                        'vehicle_id' => $vehicle->id,
                                        'dateiname'  => $filename,
                                        'sortierung' => $i + 1,
                                    ]);
                                    $imageCount++;
                                }
                            } else {
                                foreach ($imageUrls as $i => $url) {
                                    VehicleImage::create([
                                        'vehicle_id' => $vehicle->id,
                                        'dateiname'  => $url,
                                        'sortierung' => $i + 1,
                                    ]);
                                    $imageCount++;
                                }
                            }
                        }
                    }

                    $this->cleanupDir($tmpDir);
                    @unlink($zipPath);

                    Notification::make()
                        ->title('mobile.de Import abgeschlossen')
                        ->body("$created neu, $updated aktualisiert, $imageCount Bilder importiert.")
                        ->success()
                        ->send();
                }),
        ];
    }

    private function cleanupDir(string $dir): void
    {
        if (! is_dir($dir)) return;

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $file) {
            $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
        }

        rmdir($dir);
    }
}

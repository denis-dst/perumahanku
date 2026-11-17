<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Unit;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SiteplanService
{
    /**
     * Slice an area from a siteplan image and save to disk
     */
    public function sliceArea(string $sourceImagePath, float $xPct, float $yPct, float $wPct, float $hPct, string $filename): ?string
    {
        if (! file_exists($sourceImagePath)) {
            return null;
        }

        $imageInfo = getimagesize($sourceImagePath);
        if (! $imageInfo) {
            return null;
        }

        $origW = $imageInfo[0];
        $origH = $imageInfo[1];
        $mime = $imageInfo['mime'];

        $srcImg = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($sourceImagePath),
            'image/png' => @imagecreatefrompng($sourceImagePath),
            'image/webp' => @imagecreatefromwebp($sourceImagePath),
            default => null,
        };

        if (! $srcImg) {
            return null;
        }

        // Calculate pixel coordinates
        $x = max(0, min($origW, ($xPct / 100) * $origW));
        $y = max(0, min($origH, ($yPct / 100) * $origH));
        $w = max(10, min($origW - $x, ($wPct / 100) * $origW));
        $h = max(10, min($origH - $y, ($hPct / 100) * $origH));

        $slice = imagecrop($srcImg, [
            'x' => (int) $x,
            'y' => (int) $y,
            'width' => (int) $w,
            'height' => (int) $h,
        ]);

        imagedestroy($srcImg);

        if (! $slice) {
            return null;
        }

        $dir = storage_path('app/public/slices');
        if (! File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $targetPath = $dir.'/'.$filename;
        imagejpeg($slice, $targetPath, 90);
        imagedestroy($slice);

        return 'slices/'.$filename;
    }

    /**
     * Auto generate and slice units for a sample siteplan (like sample-siteplan.jpg)
     */
    public function autoSliceSampleSiteplan(Project $project, string $imagePath): int
    {
        $types = $project->houseTypes;
        if ($types->isEmpty()) {
            return 0;
        }

        $typeAsoka = $types->first();
        $typeBougen = $types->count() > 1 ? $types->get(1) : $typeAsoka;
        $typeCempaka = $types->count() > 2 ? $types->get(2) : $typeBougen;

        // Definition of lots matching sample-siteplan.jpg (1600x1137)
        // Coordinates in percentage: [x_pct, y_pct, w_pct, h_pct] where (x,y) is top-left
        $lots = [
            // BLOCK A (Top Left)
            [
                'block' => 'A',
                'number' => '01',
                'type_id' => $typeAsoka->id,
                'x' => 9.4, 'y' => 36.0, 'w' => 5.2, 'h' => 6.2,
                'facing' => 'Barat Laut (Taman Entrance)',
                'status' => 'available',
                'pov_desc' => 'POV dari Teras Kavling A-01: Menghadap taman entrance gerbang utama dan pavilion cluster. Posisi paling dekat ke security.',
                'pov_photo' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'block' => 'A',
                'number' => '02',
                'type_id' => $typeAsoka->id,
                'x' => 16.2, 'y' => 31.0, 'w' => 3.8, 'h' => 5.0,
                'facing' => 'Utara',
                'status' => 'available',
                'pov_desc' => 'POV Kavling A-02: View langsung ke arah jogging track dan aliran danau buatan mini.',
                'pov_photo' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'block' => 'A',
                'number' => '03',
                'type_id' => $typeBougen->id,
                'x' => 20.0, 'y' => 41.5, 'w' => 9.5, 'h' => 7.2,
                'facing' => 'Timur (Matahari Pagi)',
                'status' => 'booked',
                'pov_desc' => 'POV Kavling A-03 (Hook Spesial): Menghadap matahari terbit dengan view taman boulevard utama dan mini amphitheatre.',
                'pov_photo' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'block' => 'A',
                'number' => '04',
                'type_id' => $typeAsoka->id,
                'x' => 12.5, 'y' => 41.5, 'w' => 6.0, 'h' => 7.2,
                'facing' => 'Selatan',
                'status' => 'available',
                'pov_desc' => 'POV Kavling A-04: View pepohonan rindang cluster A.',
                'pov_photo' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1000&q=80',
            ],

            // BLOCK B (Bottom Left)
            [
                'block' => 'B',
                'number' => '01',
                'type_id' => $typeAsoka->id,
                'x' => 9.7, 'y' => 62.8, 'w' => 5.6, 'h' => 6.0,
                'facing' => 'Utara',
                'status' => 'available',
                'pov_desc' => 'POV Kavling B-01: Posisi menghadap area jalan taman pedestrian yang bebas lalu lalang kendaraan besar.',
                'pov_photo' => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'block' => 'B',
                'number' => '02',
                'type_id' => $typeBougen->id,
                'x' => 19.5, 'y' => 62.8, 'w' => 7.0, 'h' => 6.0,
                'facing' => 'Utara',
                'status' => 'available',
                'pov_desc' => 'POV Kavling B-02: Menghadap taman komunal tengah dan jalur pedestrian asri.',
                'pov_photo' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'block' => 'B',
                'number' => '03',
                'type_id' => $typeAsoka->id,
                'x' => 13.8, 'y' => 73.8, 'w' => 7.0, 'h' => 7.5,
                'facing' => 'Selatan (Dekat Playground)',
                'status' => 'available',
                'pov_desc' => 'POV Kavling B-03: Sangat cocok untuk keluarga dengan anak kecil, langsung berhadapan dengan Children Playground dan Seating Area.',
                'pov_photo' => 'https://images.unsplash.com/photo-1600566753376-12c8ab7fb75b?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'block' => 'B',
                'number' => '04',
                'type_id' => $typeAsoka->id,
                'x' => 20.8, 'y' => 73.8, 'w' => 5.5, 'h' => 7.5,
                'facing' => 'Selatan',
                'status' => 'sold',
                'pov_desc' => 'POV Kavling B-04 (Terjual): View area seating area santai.',
                'pov_photo' => 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?auto=format&fit=crop&w=1000&q=80',
            ],

            // BLOCK C (Bottom Center)
            [
                'block' => 'C',
                'number' => '01',
                'type_id' => $typeBougen->id,
                'x' => 31.2, 'y' => 62.8, 'w' => 6.8, 'h' => 6.5,
                'facing' => 'Utara',
                'status' => 'available',
                'pov_desc' => 'POV Kavling C-01: Menghadap langsung ke jembatan lengkung dan danau tematik buatan.',
                'pov_photo' => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'block' => 'C',
                'number' => '02',
                'type_id' => $typeAsoka->id,
                'x' => 38.0, 'y' => 62.8, 'w' => 4.6, 'h' => 6.5,
                'facing' => 'Utara',
                'status' => 'available',
                'pov_desc' => 'POV Kavling C-02: View terbuka ke arah sungai mini alami dan taman hijau.',
                'pov_photo' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'block' => 'C',
                'number' => '03',
                'type_id' => $typeBougen->id,
                'x' => 31.2, 'y' => 74.0, 'w' => 6.4, 'h' => 7.0,
                'facing' => 'Selatan',
                'status' => 'available',
                'pov_desc' => 'POV Kavling C-03: View jalan cluster bebas banjir dengan penghijauan tepi jalan.',
                'pov_photo' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'block' => 'C',
                'number' => '04',
                'type_id' => $typeBougen->id,
                'x' => 37.6, 'y' => 74.0, 'w' => 5.6, 'h' => 7.0,
                'facing' => 'Selatan',
                'status' => 'booked',
                'pov_desc' => 'POV Kavling C-04: View menghadap taman gazebo bundar.',
                'pov_photo' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'block' => 'C',
                'number' => '05 (Hook)',
                'type_id' => $typeCempaka->id,
                'x' => 43.2, 'y' => 74.0, 'w' => 8.2, 'h' => 7.0,
                'facing' => 'Timur & Selatan',
                'status' => 'available',
                'pov_desc' => 'POV Kavling Hook C-05: Kavling hook istimewa 2 lantai dengan pemandangan 180 derajat danau tematik dan jembatan kayu.',
                'pov_photo' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1000&q=80',
            ],

            // BLOCK D (Bottom Right)
            [
                'block' => 'D',
                'number' => '01 (Hook)',
                'type_id' => $typeCempaka->id,
                'x' => 61.2, 'y' => 55.0, 'w' => 7.5, 'h' => 6.5,
                'facing' => 'Utara & Barat',
                'status' => 'available',
                'pov_desc' => 'POV Kavling Hook D-01: Posisi hook menghadap bundaran taman dan gazebo danau.',
                'pov_photo' => 'https://images.unsplash.com/photo-1600566753376-12c8ab7fb75b?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'block' => 'D',
                'number' => '02',
                'type_id' => $typeBougen->id,
                'x' => 69.4, 'y' => 55.0, 'w' => 5.0, 'h' => 6.5,
                'facing' => 'Utara',
                'status' => 'available',
                'pov_desc' => 'POV Kavling D-02: Menghadap pepohonan asri dan dekat fitness corner.',
                'pov_photo' => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'block' => 'D',
                'number' => '03',
                'type_id' => $typeBougen->id,
                'x' => 74.4, 'y' => 55.0, 'w' => 7.5, 'h' => 6.5,
                'facing' => 'Utara',
                'status' => 'sold',
                'pov_desc' => 'POV Kavling D-03: View area olahraga.',
                'pov_photo' => 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'block' => 'D',
                'number' => '04',
                'type_id' => $typeBougen->id,
                'x' => 69.4, 'y' => 67.2, 'w' => 5.0, 'h' => 6.5,
                'facing' => 'Selatan',
                'status' => 'available',
                'pov_desc' => 'POV Kavling D-04: Menghadap danau air jernih dan taman santai tepi air.',
                'pov_photo' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'block' => 'D',
                'number' => '05',
                'type_id' => $typeCempaka->id,
                'x' => 74.4, 'y' => 67.2, 'w' => 7.5, 'h' => 6.5,
                'facing' => 'Selatan',
                'status' => 'available',
                'pov_desc' => 'POV Kavling D-05: Rumah 2 lantai dengan balkon kamar utama langsung menghadap pantulan danau cluster.',
                'pov_photo' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1000&q=80',
            ],

            // BLOCK E (Center)
            [
                'block' => 'E',
                'number' => '01',
                'type_id' => $typeBougen->id,
                'x' => 42.8, 'y' => 33.8, 'w' => 6.8, 'h' => 6.8,
                'facing' => 'Utara (Rooftop Garden)',
                'status' => 'available',
                'pov_desc' => 'POV Kavling E-01: Pemandangan langsung ke Community Rooftop Garden yang hijau dan megah.',
                'pov_photo' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'block' => 'E',
                'number' => '02',
                'type_id' => $typeAsoka->id,
                'x' => 49.6, 'y' => 33.8, 'w' => 4.8, 'h' => 6.8,
                'facing' => 'Utara',
                'status' => 'available',
                'pov_desc' => 'POV Kavling E-02: View taman komunal terpusat.',
                'pov_photo' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'block' => 'E',
                'number' => '03',
                'type_id' => $typeBougen->id,
                'x' => 54.6, 'y' => 33.8, 'w' => 6.8, 'h' => 6.8,
                'facing' => 'Utara',
                'status' => 'booked',
                'pov_desc' => 'POV Kavling E-03: Dekat dengan lapangan badminton & basketball 3 on 3.',
                'pov_photo' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'block' => 'E',
                'number' => '04',
                'type_id' => $typeAsoka->id,
                'x' => 49.6, 'y' => 45.8, 'w' => 4.8, 'h' => 6.8,
                'facing' => 'Selatan',
                'status' => 'available',
                'pov_desc' => 'POV Kavling E-04: Menghadap bundaran jalan utama dan taman hijau.',
                'pov_photo' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'block' => 'E',
                'number' => '05',
                'type_id' => $typeBougen->id,
                'x' => 54.6, 'y' => 45.8, 'w' => 6.8, 'h' => 6.8,
                'facing' => 'Selatan',
                'status' => 'available',
                'pov_desc' => 'POV Kavling E-05: Dekat ke area BBQ taman dan amphitheatre.',
                'pov_photo' => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=1000&q=80',
            ],
        ];

        $createdCount = 0;
        foreach ($lots as $lot) {
            $sliceFile = 'slice_'.Str::slug($project->slug).'_'.strtolower($lot['block']).'_'.strtolower($lot['number']).'.jpg';
            $slicePath = $this->sliceArea($imagePath, $lot['x'], $lot['y'], $lot['w'], $lot['h'], $sliceFile);

            // Center of lot
            $centerX = $lot['x'] + ($lot['w'] / 2);
            $centerY = $lot['y'] + ($lot['h'] / 2);

            // Polygon points: 4 corners of the lot
            $polygon = [
                [$lot['x'], $lot['y']],
                [$lot['x'] + $lot['w'], $lot['y']],
                [$lot['x'] + $lot['w'], $lot['y'] + $lot['h']],
                [$lot['x'], $lot['y'] + $lot['h']],
            ];

            $type = $project->houseTypes->find($lot['type_id']) ?: $types->first();

            $unit = Unit::updateOrCreate(
                [
                    'project_id' => $project->id,
                    'block' => $lot['block'],
                    'number' => $lot['number'],
                ],
                [
                    'house_type_id' => $type->id,
                    'land_area' => $type->land_area,
                    'price' => $type->price,
                    'facing' => $lot['facing'],
                    'status' => $lot['status'],
                    'pov_description' => $lot['pov_desc'],
                    'position_x' => round($centerX, 2),
                    'position_y' => round($centerY, 2),
                    'width_pct' => round($lot['w'], 2),
                    'height_pct' => round($lot['h'], 2),
                    'polygon_coords' => $polygon,
                    'slice_image' => $slicePath,
                ]
            );

            // Sync POV Image
            $unit->images()->where('category', 'pov')->delete();
            $unit->images()->create([
                'path' => $lot['pov_photo'],
                'caption' => 'POV Nyata dari Kavling '.$unit->block.'-'.$unit->number,
                'is_primary' => true,
                'category' => 'pov',
                'sort_order' => 1,
            ]);

            $createdCount++;
        }

        return $createdCount;
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeds the full list of `size` attribute options used by the storefront filter
 * and configurable products.
 *
 * NOTE: this lives in a seeder (not only a migration) because the original data
 * migrations run BEFORE the attribute seeders on a fresh `migrate:fresh` install,
 * so at migration time the `size` attribute does not exist yet and they no-op.
 * Running this seeder after the main seed guarantees the options exist.
 *
 * Idempotent: existing options are skipped, so it is safe to re-run.
 */
class SizeOptionsSeeder extends Seeder
{
    public function run(): void
    {
        $sizeAttribute = DB::table('attributes')->where('code', 'size')->first();

        if (! $sizeAttribute) {
            $this->command?->warn('No "size" attribute found — run the main seeder first.');

            return;
        }

        $sizes = [
            '1-2', '2', '2-3', '3', '3-4', '4', '4-5', '5', '5-6', '6',
            '6-7', '6-8', '7', '7-8', '8', '8-9', '9', '9-10', '10', '10-11',
            '11', '11-12', '12', '12-13', '13', '13-14', '14', '14-15', '16',
            '20', '22', '24', '26', '28', '30', '32', '34', '36', '38', '40',
            '42', '44', '46', '48', '50', '52', '54', '56',
            'Free', 'S', 'M', 'L', 'XL', 'XXL', 'semi-stitched', 'Unstitched',
        ];

        $sortOrder = (int) (DB::table('attribute_options')
            ->where('attribute_id', $sizeAttribute->id)
            ->max('sort_order') ?? 0);

        $added = 0;

        foreach ($sizes as $size) {
            $exists = DB::table('attribute_options')
                ->where('attribute_id', $sizeAttribute->id)
                ->where('admin_name', $size)
                ->exists();

            if ($exists) {
                continue;
            }

            $sortOrder++;

            $optionId = DB::table('attribute_options')->insertGetId([
                'attribute_id' => $sizeAttribute->id,
                'admin_name'   => $size,
                'sort_order'   => $sortOrder,
            ]);

            DB::table('attribute_option_translations')->insert([
                'attribute_option_id' => $optionId,
                'locale'              => 'en',
                'label'               => $size,
            ]);

            $added++;
        }

        $this->command?->info("Size options ensured ({$added} added, ".(count($sizes) - $added).' already present).');
    }
}

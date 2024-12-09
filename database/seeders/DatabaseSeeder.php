<?php

namespace Database\Seeders;

use App\Models\Notes;
use App\Models\Tags;
use App\Models\Tasks;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        Notes::insert([
            [
                'id' => 16,
                'content' => 'https://github.com/rondeo-balos/documentations',
                'index' => null,
                'created_at' => null,
                'updated_at' => '2024-12-03 18:40:34',
            ],
            [
                'id' => 21,
                'content' => 'Pending: Cartstack https://docs.google.com/spreadsheets/d/1MGWP5mwxLo1ytkz0ACkZRilvesnrrKDImEPxB25PVGE/edit?gid=0#gid=0',
                'index' => null,
                'created_at' => null,
                'updated_at' => '2024-12-04 18:54:14',
            ],
            [
                'id' => 22,
                'content' => 'Done: Tracking: TWK, ERN',
                'index' => null,
                'created_at' => null,
                'updated_at' => '2024-12-05 19:16:30',
            ],
            [
                'id' => 23,
                'content' => 'Pending: Mini builds',
                'index' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
        ]);

        Tags::insert([
            ['id' => 1, 'tag' => 'Wordpress', 'color' => 'primary', 'created_at' => null, 'updated_at' => null],
            ['id' => 2, 'tag' => 'ClickFunnel', 'color' => 'danger', 'created_at' => null, 'updated_at' => null],
            ['id' => 3, 'tag' => 'Static', 'color' => 'warning', 'created_at' => null, 'updated_at' => '2024-12-04 22:59:59'],
            ['id' => 4, 'tag' => 'Design', 'color' => 'info', 'created_at' => null, 'updated_at' => null],
            ['id' => 5, 'tag' => 'BugTrack', 'color' => 'info', 'created_at' => null, 'updated_at' => '2024-12-04 23:00:15'],
            ['id' => 6, 'tag' => 'Convertflows', 'color' => 'success', 'created_at' => null, 'updated_at' => null],
            ['id' => 7, 'tag' => 'Mixed', 'color' => 'secondary', 'created_at' => null, 'updated_at' => '2024-12-04 23:00:23'],
            ['id' => 8, 'tag' => 'N/A', 'color' => 'secondary-emphasis', 'created_at' => null, 'updated_at' => null],
            ['id' => 9, 'tag' => 'Cart Stack', 'color' => 'primary-emphasis', 'created_at' => null, 'updated_at' => null],
        ]);

        Tasks::insert([
            [
                'id' => 54,
                'title' => 'Prepping',
                'description' => '["Opening apps","Preparing tasks"]',
                'tag' => 8,
                'start' => 1733146800,
                'end' => 1733147813,
                'created_at' => null,
                'updated_at' => '2024-12-06 21:58:23',
            ],
            [
                'id' => 55,
                'title' => 'TWK Cart - NTF',
                'description' => '["(thrivecart) fixing ntf cart redirecting to /unlimited/ upsell","https://millionairepublishing.slack.com/archives/C07TAG79F61/p1733084642070409"]',
                'tag' => 3,
                'start' => 1733147760,
                'end' => 1733160300,
                'created_at' => null,
                'updated_at' => '2024-12-06 21:58:23',
            ],
            [
                'id' => 56,
                'title' => 'Split Test Task',
                'description' => '["Declare the control the winner on this split test, running all traffic to the control from here on","https://app.convert.com/accounts/10024418/projects/10024447/experiences/100251864/summary","Create a duplicate of that control page with a new url (could be v5 or whatever)","Create another 50/50 split test with that new control and the variant from the test above and share the relevant links here?","50/50 split test\ncontrol: https://lp.stockstotrade.com/algo-trader/v5/\nvariation: https://lp.stockstotrade.com/algo-trader/v5b/\nreport: https://app.convert.com/accounts/10024418/projects/10024447/experiences/100252251/report"]',
                'tag' => 7,
                'start' => 1733166900,
                'end' => 1733168160,
                'created_at' => null,
                'updated_at' => '2024-12-06 21:58:23',
            ],
            [
                'id' => 57,
                'title' => 'BNA - Cart Platinum',
                'description' => '["Pastel updates: https://roguetradingsummit.com/cart-platinum/"]',
                'tag' => 3,
                'start' => 1733328688,
                'end' => 1733330267,
                'created_at' => null,
                'updated_at' => '2024-12-06 21:58:23',
            ],
            [
                'id' => 58,
                'title' => 'BNA - Cart Platinum',
                'description' => '["More pastel updates"]',
                'tag' => 3,
                'start' => 1733330838,
                'end' => 1733335425,
                'created_at' => null,
                'updated_at' => '2024-12-06 21:58:22',
            ],
            [
                'id' => 60,
                'title' => 'APB (Woo) - Clone Unlimited upgrade in woo',
                'description' => '["Create a new version of the AI Power Blueprint Unlimited Upgrade product in Woo for $1195","Swap that in for the upgrade that is used in this funnel’s “Lifetime Upgrade - AI Power Blueprint B” upsell (Variation-1)\n- https://trading.timothysykes.com/wp-admin/admin.php?page=cartflows&path=flows&action=wcf-edit-flow&flow_id=2336"]',
                'tag' => 1,
                'start' => 1733337596,
                'end' => 1733340961,
                'created_at' => null,
                'updated_at' => '2024-12-06 21:58:22',
            ],
            [
                'id' => 63,
                'title' => 'Cart Stack',
                'description' => '["Added Purchase Confirmation Integration for TS1XGPT & APB","Added Tracking to TWK"]',
                'tag' => 9,
                'start' => 1733341387,
                'end' => 1733344130,
                'created_at' => null,
                'updated_at' => '2024-12-06 21:58:22',
            ],
            [
                'id' => 66,
                'title' => 'Cart Stack - ERN & TWK Tracking',
                'description' => '["Add CartStack Tracking script"]',
                'tag' => 9,
                'start' => 1733409885,
                'end' => 1733411651,
                'created_at' => null,
                'updated_at' => '2024-12-06 21:58:22',
            ],
            [
                'id' => 67,
                'title' => 'Updating plugins and themes',
                'description' => '["Multisite: Update plugins to the latest version"]',
                'tag' => 1,
                'start' => 1733424702,
                'end' => 1733430093,
                'created_at' => null,
                'updated_at' => '2024-12-06 21:58:22',
            ],
            [
                'id' => 68,
                'title' => 'Prepping',
                'description' => '["Opening apps","Preparing tasks"]',
                'tag' => 8,
                'start' => 1733407236,
                'end' => 1733408161,
                'created_at' => null,
                'updated_at' => '2024-12-06 21:58:22',
            ],
            [
                'id' => 69,
                'title' => 'Spyder unlimited cart',
                'description' => '["Update cart copy","Link: https://stealthtradingsummit.com/cart/lifetime-upgrade/","Copy: https://docs.google.com/document/d/1zs59piE2TfnQPIiYSiZcA2NNZE850RDMd2FnP9Yb3RY/edit?tab=t.0","backed up index-bak-12-6-24.php"]',
                'tag' => 3,
                'start' => 1733505959,
                'end' => 1733511651,
                'created_at' => null,
                'updated_at' => '2024-12-06 21:58:22',
            ],
            [
                'id' => 70,
                'title' => 'IRIS Mini Build',
                'description' => '["Building reg page","Building confirmation page / typ","Duplicating onecl.php","Doing Tests"]',
                'tag' => 3,
                'start' => 1733511652,
                'end' => 1733518409,
                'created_at' => null,
                'updated_at' => '2024-12-06 21:58:22',
            ],
        ]);
    }
}

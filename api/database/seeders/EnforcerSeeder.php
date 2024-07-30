<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnforcerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('enforcers')->insert([
            'icon' => 'groups',
            'title' => 'Conduct A Post Meeting',
            'content' => '  -Properly fill up the compliance monitoring form complete all the needed details<br>
                            -Compare the citation tickets to the SF Monitoring Tool<br>
                            -Count all confiscated items and surrender to Smoke-Free Office<br>
                            -Endorse the white ticket to your barangay treasurer or secretary for claiming later of violators. Discuss issues and concerns<br>
                            -Document and make proper reports endorsed to the concerned barangay captain<br>
                            -Schedule of next monitoring visit<br>',
        ]);

        DB::table('enforcers')->insert([
            'icon' => 'summarize',
            'title' => 'Monthly Report',
            'content' => '  -Fill out Barangay Monitoring Tool<br>
                            -Attach photocopy of SF2 (Compliance Monitoring Tool)<br>
                            -Submit every 5th of the month at the SF office or through here
                            -Barangay should have own copy<br>
                            -Post pictures at Smoke-Free Facebook page to include number of establishments monitored,<br>
                            -Issued citation ticket, violations and individual violators<br>',
        ]);

        DB::table('enforcers')->insert([
            'icon' => 'check_circle',
            'title' => 'Prepared and Ready Enforcer',
            'content' => '  -An enforcer must be prepared and ready prior to enforcement.<br>
                            -Wear Uniform and ID; Bring compliance monitoring form and Citation Ticket<br>
                            -Bring Pen, tape, plastic or eco bag; Bring IEC materials; Documentation device re: Camera<br>',
        ]);

        DB::table('enforcers')->insert([
            'icon' => 'diversity_3',
            'title' => 'Enforcement Team',
            'content' => '  -Enforcement is not a one-man job. Everyone in the community can apprehend but only the deputized enforcer can issue a citation ticket.<br>
                            -Every inspection and monitoring MUST be composed of a team: (Any 3)<br>
                            <ul><li>Deputized Enforcer; Police Representative; Barangay Kagawads</li>
                            <li>Sangguniang Kabataan; Tanods/ BPAT; BHW/ BNS/ BNAO</li>
                            <li>Members of the DHST; Sanitary Inspector; Others (identified by the Health District)</li></ul><br>',
        ]);

        DB::table('enforcers')->insert([
            'icon' => 'face',
            'title' => 'Attitude and Capacity',
            'content' => '  -An enforcer KNOWS the policies. Familiarize self on the ordinance including RA 9211 and EO 26.<br>
                            -An enforcer must be CALM at all times. Maximum tolerance<br>
                            -Safety of enforcers is a priority<br>
                            -Avoid heated arguments<br>
                            -Consider weather conditions when scheduling inspections<br>',
        ]);

        DB::table('enforcers')->insert([
            'icon' => 'pageview',
            'title' => 'Spot Violation in an Establisment',
            'content' => '  -Ocular survey of the store/establishment. Only enter the store as far as any customer can. Never force your way in.<br>
                            -Check for any tobacco advertisement or promotional materials.<br>
                            -Posters, stickers, ash tray, tissue holders, lamp<br>
                            -Check for open display of tobacco products<br>
                            -Check for opened packs of cigarette that are possibly sold per stick<br>',
        ]);
    }
}

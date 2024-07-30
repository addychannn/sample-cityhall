<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AboutSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('about_sections')->insert([
            'title' => 'About Us',
            'content' => 'We are a global model in smoke-free and healthy environment. Baguio City is a Smoke-Free City, and we are one of the cities in the Philippines that strictly enforces a Smoke-Free ordinance. Smoke-Free Baguio aims to safeguard public health by protecting them from the harmful effects of smoking, vaping and tobacco consumption.',
            'protected'=>true,
            'show'=>true
        ]);

        DB::table('about_sections')->insert([
            'title'=>'Purpose',
            'content'=>'Our purpose is to safeguard public health and ensure the well-being of Baguio citizens by protecting them from harmful effects of smoking and tobacco consumption, reduce potential exposure to tobacco marketing and advertising, restrict its accessibility, and provide a supportive and conducive environment for tobacco users to quit.  The primary purpose of smokefree laws and policies is to protect people who do not smoke from secondhand smoke. However, smokefree laws can also motivate and help tobacco users quit and prevent initiation of tobacco use.',
            'protected'=>true,
            'show'=>true
        ]);

        DB::table('about_sections')->insert([
            'title'=>'Programs',
            'content'=>'The ordinance resulted to the creation of the Smoke-Free Baguio Task Force.
            Since then, Smoke-Free Baguio supports the Smoke-Free Baguio City Ordinance through:
            -Enforcement, monitoring and inspection in different barangays in Baguio City
            -Conducting advocacy and enforcement training, seminars and workshops',
            'protected'=>true,
            'show'=>true
        ]);

        DB::table('about_sections')->insert([
            'title'=>'Smoke-Free Baguio City Ordinance',
            'content'=>'Prohibiting the use, sale, distribution, and advertisement of ciggaretes and other tobacco products in certain places, imposing penalties for violations thereof and providing funds thereof, and for other purposes.
            The ordinance resulted to the creation of the Smoke-Free Baguio Task Force. Since then, Smoke-Free Baguio supports the Smoke-Free Baguio City Ordinance through Enforcement, monitoring and inspection in different barangays in Baguio City Conducting advocacy and enforcement training, seminars and workshops',
            'protected'=>true,
            'show'=>true
        ]);

    }
}

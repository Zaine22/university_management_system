<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Certificate Program
        // java
        $java = ['Java Standard Edition (J2SE)', 'Basic Concept in Java', 'OOP Concept in Java',
            'Collection in Java', 'Graphic User Interface (GUI)', 'Database Module'];
        foreach ($java as $subject) {
            Subject::create([
                'subject_name' => $subject,
                'subject_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.',
            ]);
        }

        // php
        $php = ['Fundamental of Web Development', 'Environmental Setup', 'Various Data Types',
            'Operator, Array, Control Statement', 'Object Oriented Programming', 'Session, Cookies', 'PHP with SQL'];
        foreach ($php as $subject) {
            Subject::create([
                'subject_name' => $subject,
                'subject_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.',
            ]);
        }

        // python
        $python = ['Introduction to Python', 'Download & Install Python', 'Getting Start with Python ', 'Python Data Structure', 'Graphic User Interface (GUI)',
            'File input, output', 'Relational Database SQL/ SQL Library in Python', 'SQL and Python/ SQL injection Query', 'Statistical Data Analysis with Python'];
        foreach ($python as $subject) {
            Subject::create([
                'subject_name' => $subject,
                'subject_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.',
            ]);
        }

        // cloud
        $cloudComputing = ['What is Cloud Computing?', 'Amazon EC2 and Amazon EC2 Instance Storage', 'Elastic Load Balancing & Auto Scaling Group / Amazon S3', 'Databases & Analytics / Other Computer Services',
            'Deploying & Managing Infrastructure at Scale', 'Global Infrastructure / Cloud Integration', 'Cloud Monitoring / Amazone VPC / Security & Compliance', 'Machine Learning / Account Management, Billing, & Support',
            'Advanced Identity, Other AWS Services, AWS Architecting & Ecosystem'];
        foreach ($cloudComputing as $subject) {
            Subject::create([
                'subject_name' => $subject,
                'subject_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.',
            ]);
        }

        // ui/ux
        $uiUx = ['UI Fundamentals', 'Learn Figma', 'UX Design Concepts',
            'Wireframing for UI Designers', 'UI or Visual Design Concepts', 'Portfolio Website'];
        foreach ($uiUx as $subject) {
            Subject::create([
                'subject_name' => $subject,
                'subject_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.',
            ]);
        }

        // business english
        $businessEnglish = ['Introduction to Busincess English', 'Email Writing', 'Presentations', 'Meeting',
            'Negotiations', 'Socializing', 'Vocaulary and Grammar Review', 'Cultural Differences in Communication'];
        foreach ($businessEnglish as $subject) {
            Subject::create([
                'subject_name' => $subject,
                'subject_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.',
            ]);
        }

        // network
        $network = ['Introduction to Computer Networks', 'Computer topology', 'Network Protocol', 'Network Configuration',
            'Transmission medias', 'Addressing', 'Server Installation', 'Network Troubleshooting'];
        foreach ($businessEnglish as $subject) {
            Subject::create([
                'subject_name' => $subject,
                'subject_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.',
            ]);
        }

        // hardware
        $hardware = ['Hardware Component', 'Software Component', 'System Component',
            'Hardware Installation and Configuration', 'Window Installation', 'PC debugging', 'Troubleshooting and Maintenance'];
        foreach ($hardware as $subject) {
            Subject::create([
                'subject_name' => $subject,
                'subject_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.',
            ]);
        }

        // Diploma Program
        // Information Technology
        $IT = ['Computer Theory', 'System Component', 'Hardware/ Software/ Operating System', 'Data Structures & Algorithms', 'Data Communication & Network',
            'Security', 'Microsoft Office', 'Introduction to Web Development', 'Introduction to Java Programming', 'Practical Networking'];
        foreach ($IT as $subject) {
            Subject::create([
                'subject_name' => $subject,
                'subject_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.',
            ]);
        }

        // web development
        $web = ['HTML', 'CSS', 'Bootstrap, jQuery', 'JavaScript',
            'React.js, Node.js', 'API', 'Figma', 'Git & GitHub'];
        foreach ($web as $subject) {
            Subject::create([
                'subject_name' => $subject,
                'subject_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.',
            ]);
        }

        // business IT
        $businessIT = ['Management Information System', 'Content Management System', 'Digital Marketing', 'Business Strategy',
            'Project Management', 'Strategic Leadership', 'IT Fundamentals', 'Microsoft Office'];
        foreach ($businessIT as $subject) {
            Subject::create([
                'subject_name' => $subject,
                'subject_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.',
            ]);
        }

        // Bachelor degree program
        // bachelor
        $bachelor = ['Data Structure & Algorithm ', 'Statistics', 'Visualization', 'Database Management System',
            'Big Data Analysis', 'Machine Learning & AI', 'Web & Mobile Development', 'Project Management'];
        foreach ($bachelor as $subject) {
            Subject::create([
                'subject_name' => $subject,
                'subject_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.',
            ]);
        }

        // University foundation
        $uniFoundation = ['English', 'Mathematics', 'Computer System',
            'Computer Application', 'Business Administration', 'Project Based Learning'];
        foreach ($uniFoundation as $subject) {
            Subject::create([
                'subject_name' => $subject,
                'subject_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.',
            ]);
        }

        // Year 1
        $year1 = ['English', 'Mathematics', 'Information Technology',
            'Programming', 'Human Interface and Multimedia', 'Project Based Learning'];
        foreach ($year1 as $subject) {
            Subject::create([
                'subject_name' => $subject,
                'subject_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.',
            ]);
        }

        // Year 2
        $year2 = ['English', 'Mathematics', 'Data Science',
            'Programming', 'System Analysis and Design', 'Project Based Learning'];
        foreach ($year2 as $subject) {
            Subject::create([
                'subject_name' => $subject,
                'subject_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.',
            ]);
        }

        // Degree Program
        // Master
        $master = ['Data Structures and Algorithms ', 'Statistical Analysis for Data Science ', 'Data Analytics and Visualization', 'Big Data Technologies and Analytics ', 'Machine Learning and Artificial Intelligence  ',
            'Programming languages and software development ', 'Cloud Computing and Distributed Systems ', 'Ethical and Legal Issues Related to Data Science ', 'Capstone Project or Research Paper'];
        foreach ($master as $subject) {
            Subject::create([
                'subject_name' => $subject,
                'subject_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.',
            ]);
        }

        // Program
        // RI designated
        $riDesignated = ['ITPEC(IP)', 'IP Preparation Book + exam preparation', 'Corporate and Legal Affairs', 'Business Strategy', 'System Strategy', 'Development Technology',
            'Project Management', 'Service Management', 'Basic Theory', 'Computer System', 'Technology Element ', 'Pseudo code + Python or PHP', 'ITPEC(FE)',
            'FE Textbook Vol.1', 'Hardware', 'Information Processing System', 'Software', 'Database', 'Network', 'Security',
            'Data Structure and Algorithm', 'FE Textbook Vol.2', 'Corporate and Legal Affairs', 'Business Strategy', 'Information System Strategy', 'Development Technology',
            'Project Management', 'Service Management', 'System Audit and Internal Control', 'Exam Preparation', 'Pseudo code + Python or PHP', 'Diploma in IT',
            'Diploma in Web Development', 'Japanese Basic + N5 + N4 + N3', 'Software Development', 'Project Based Learning', 'On Job Training '];
        foreach ($riDesignated as $subject) {
            Subject::create([
                'subject_name' => $subject,
                'subject_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.',
            ]);
        }

        // FE Preparation
        $fePreparation = ['FE Textbook Vol.1', 'Hardware', 'Information Processing System', 'Software',
            'Database', 'Network', 'Security', 'Data Structure and Algorithm', 'FE Textbook Vol.2',
            'Corporate and Legal Affairs', 'Business Strategy', 'Information System Strategy', 'Development Technology', 'Project Management',
            'Service Management', 'System Audit and Internal Control', 'Exam Preparation', 'Pseudo code + Python or PHP', 'Japanese Basic + N5 + N4 + N3'];
        foreach ($fePreparation as $subject) {
            Subject::create([
                'subject_name' => $subject,
                'subject_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.',
            ]);
        }

        // FE Intensive
        $feIntensive = ['ITPEC Fundamental Engineering Volume (1+2) Overview', 'Part A (Short Question) Exam Practice',
            'Part B (Algorithm+ Pseudo+ Information Security) Exam Practice'];
        foreach ($feIntensive as $subject) {
            Subject::create([
                'subject_name' => $subject,
                'subject_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.',
            ]);
        }
    }
}

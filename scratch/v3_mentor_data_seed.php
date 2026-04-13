<?php
require_once __DIR__ . '/../includes/db.php';

echo "--- Populating Mentor Achievements (Ugandan Context) ---\n";

$mentors = [
    [
        'name' => 'Sarah Namubiru',
        'field' => 'Awarded the 2024 "National Medical Excellence Award" by the Ministry of Health for improving rural clinic accessibility.',
        'academic' => 'PhD in Epidemiology from Mbarara University of Science & Technology (MUST); Graduated with First Class Distinction.',
        'research' => 'Principal Researcher for the "MUST-Health 2023" project, implementing a mobile-based diagnostic tool for maternal health in Western Uganda.'
    ],
    [
        'name' => 'John Mugisha',
        'field' => 'Successfully defended 3 major IP cases in the High Court of Kampala; Recognized as a Leading Counsel in Commercial Law.',
        'academic' => 'Master of Laws (LLM) from Makerere University; Recipient of the Academic Excellence Grant.',
        'research' => 'Co-author of the "Uganda Digital Economy Legal Framework 2024"; Lead implementer of the IP-Protection Toolkit for local startups.'
    ],
    [
        'name' => 'Daniel Kibombo',
        'field' => 'Successfully scaled two Fintech startups from 0 to 50k active users in Kampala; Named "Young Entrepreneur of the Year" 2022.',
        'academic' => 'Master of Business Administration (MBA) from Makerere University (MUK); Bachelor of IT from Kyambogo.',
        'research' => 'Led the implementation of the first regional Mobile Money wallet for decentralized agricultural trading in East Africa.'
    ]
];

foreach ($mentors as $m) {
    try {
        $stmt = $pdo->prepare("UPDATE individuals SET achievements_field = ?, achievements_academic = ?, research_implementations = ? WHERE full_name = ? AND is_mentor = 1");
        $stmt->execute([$m['field'], $m['academic'], $m['research'], $m['name']]);
        echo "Updated Mentor: {$m['name']}\n";
    } catch (Exception $e) {
        echo "Error updating {$m['name']}: " . $e->getMessage() . "\n";
    }
}

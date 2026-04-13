<?php
/**
 * MarkGigs Ugandan Data Seeder
 */
require_once __DIR__ . '/../includes/db.php';

echo "--- MarkGigs Ugandan Data Seeder ---\n";

// Password hash for 'password123'
$pass_hash = password_hash('password123', PASSWORD_DEFAULT);

// 1. CLEAR EXISTING TEST DATA (Optional, but helps with clean seed)
// $pdo->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE users; TRUNCATE individuals; TRUNCATE companies; TRUNCATE posts; TRUNCATE opportunities; SET FOREIGN_KEY_CHECKS = 1;");

function createUser($email, $hash, $role) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, role, is_verified) VALUES (?, ?, ?, 1)");
    $stmt->execute([$email, $hash, $role]);
    return $pdo->lastInsertId();
}

// --- SEED INDIVIDUALS ---
$individuals = [
    ['brian@makerere.ug', 'Brian Okello', 'Software Engineering Student', 'Passionate about build solutions for Uganda\'s transport sector.', 'Makerere University (MUK)', 'BSc Computer Science', '2026', 'Kampala', 'Python, React, PHP', 0, ''],
    ['sarah.n@must.ac.ug', 'Sarah Namubiru', 'Senior Medical Researcher', 'Alumni of MUST with 10 years in clinical trials and public health.', 'Mbarara University of Science & Technology (MUST)', 'Medicine & Surgery', '2014', 'Mbarara', 'Public Health, Research, Lab Management', 1, 'Medical Research, Career in Health'],
    ['john.m@ucu.ac.ug', 'John Mugisha', 'Corporate Lawyer', 'Legal consultant specializing in Uganda\'s intellectual property laws.', 'Uganda Christian University (UCU)', 'Bachelor of Laws', '2018', 'Kampala', 'IP Law, Mediation, Commercial Law', 1, 'Law Career Path, Ethics'],
    ['fiona.a@kyu.ac.ug', 'Fiona Atuhaire', 'Civil Engineering Finalist', 'Interested in sustainable urban development in Jinja and Entebbe.', 'Kyambogo University', 'Engineering', '2025', 'Jinja', 'AutoCAD, Project Management', 0, ''],
    ['daniel.k@markgig.com', 'Daniel Kibombo', 'Tech Founder & Mentor', 'Serial entrepreneur in the Kampala tech ecosystem.', 'Makerere University (MUK)', 'Business Administration', '2015', 'Kampala', 'FinTech, Startups, Scaling', 1, 'Product Development, Pitching'],
    ['mary.j@must.ac.ug', 'Mary Joy', 'Computer Science Alumna (MUST)', 'Recent graduate currently building web apps for local SMEs.', 'Mbarara University of Science & Technology (MUST)', 'Computer Science', '2023', 'Mbarara', 'JS, Node.js, SQL', 0, '']
];

$individual_ids = [];
foreach ($individuals as $ind) {
    try {
        $uid = createUser($ind[0], $pass_hash, 'individual');
        $stmt = $pdo->prepare("INSERT INTO individuals (user_id, full_name, headline, bio, institution, course, graduation_year, location, skills, is_mentor, mentor_expertise, mentor_availability) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'available')");
        $stmt->execute([$uid, $ind[1], $ind[2], $ind[3], $ind[4], $ind[5], $ind[6], $ind[7], $ind[8], $ind[9], $ind[10]]);
        $individual_ids[] = ['user_id' => $uid, 'profile_id' => $pdo->lastInsertId(), 'name' => $ind[1]];
        echo "Created Individual: {$ind[1]}\n";
    } catch (Exception $e) { echo "Error creating {$ind[1]}: " . $e->getMessage() . "\n"; }
}

// --- SEED COMPANIES ---
$companies = [
    ['hr@mtn.co.ug', 'MTN Uganda', 'Telecommunications', 'Uganda\'s leading telecom provider connecting millions.', 'Kampala, Hannington Road', 'Unstoppable together.'],
    ['jobs@stanbic.co.ug', 'Stanbic Bank Uganda', 'Banking & Finance', 'Moving Forward. Serving Uganda\'s banking needs for decades.', 'Kampala, Crested Towers', 'Leading financial services provider.'],
    ['careers@safeboda.com', 'SafeBoda', 'Tech / Logistics', 'Revolutionizing urban transportation in Kampala.', 'Kampala, Kyadondo Rd', 'Better transportation for everyone.'],
    ['hello@innovationvillage.co.ug', 'The Innovation Village', 'Tech Hub / Accelerator', 'Creating the largest tech hub for entrepreneurs in Uganda.', 'Kampala, Chwa II Rd', 'Unlocking the potential of entrepreneurs.'],
    ['admin@rockethealth.ug', 'Rocket Health', 'HealthTech', 'Connecting you to quality healthcare services across Uganda.', 'Kampala, Bukoto', 'Healthcare at your fingertips.']
];

$company_user_ids = [];
$company_ids = [];
foreach ($companies as $com) {
    try {
        $uid = createUser($com[0], $pass_hash, 'company');
        $stmt = $pdo->prepare("INSERT INTO companies (user_id, name, industry, description, location) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$uid, $com[1], $com[2], $com[3], $com[4]]);
        $cid = $pdo->lastInsertId();
        $company_user_ids[] = $uid;
        $company_ids[] = ['id' => $cid, 'name' => $com[1], 'user_id' => $uid];
        echo "Created Company: {$com[1]}\n";
    } catch (Exception $e) { echo "Error creating {$com[1]}: " . $e->getMessage() . "\n"; }
}

// --- SEED OPPORTUNITIES ---
$opps = [
    [$company_ids[0]['id'], 'Software Engineering Intern', 'internship', 'Join our Digital Division to help build Next-Gen billing apps.', 'Kampala (On-site)', 'Fluent in Java or PHP, Student in CS/SE.'],
    [$company_ids[1]['id'], 'Graduate Trainee - Finance', 'job', 'A 12-month program for finance graduates to rotate through departments.', 'Kampala', 'First-class degree in BCOM/BBA, leadership skills.'],
    [$company_ids[2]['id'], 'Backend Developer (Node.js)', 'gig', 'Short term contract to optimize our ride-hailing API.', 'Remote (Uganda)', '3+ yrs experience in Node.js, Express.'],
    [$company_ids[3]['id'], 'Community Manager Intern', 'internship', 'Engage with our founder community and organize tech events.', 'Kampala', 'Excellent communication, social media savvy.'],
    [$company_ids[4]['id'], 'Data Analyst (Health Analytics)', 'job', 'Analyze healthcare data to improve patient delivery metrics.', 'Kampala (Bukoto)', 'Strong in SQL, PowerBI, and Health stats.'],
    [$company_ids[0]['id'], 'UX Design Gig', 'gig', 'Redesigning the MoMo App interface.', 'Remote', 'Figma expert, experience in mobile UX.']
];

foreach ($opps as $opp) {
    try {
        $stmt = $pdo->prepare("INSERT INTO opportunities (company_id, title, type, description, location, requirements) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute($opp);
        echo "Created Opportunity: {$opp[1]}\n";
    } catch (Exception $e) { echo "Error creating Opp {$opp[1]}: " . $e->getMessage() . "\n"; }
}

// --- SEED POSTS ---
$posts = [
    [$individual_ids[0]['user_id'], 'Excited to start my final semester at Makerere University! Looking for Capstone project partners.', 'update'],
    [$company_user_ids[0], 'MTN Uganda is looking for fresh talent in Kampala. Check our Jobs tab for the latest internships!', 'achievement'],
    [$individual_ids[1]['user_id'], 'Tips for MUST students: Start planning your internship early. We have many slots opening in Mbarara.', 'update'],
    [$individual_ids[2]['user_id'], 'Just provided a mentorship session on IP law at Innovation Village. Great energy from Kampala startups!', 'achievement'],
    [$company_user_ids[3], 'Join us this Sunday at Innovation Village for a Hackathon on FinTech solutions.', 'question'],
    [$individual_ids[4]['user_id'], 'Who is coming for the Tech-Talk on Friday at Kyambogo?', 'question'],
    [$individual_ids[1]['user_id'], 'New medical research paper published on health delivery in Western Uganda. So proud!', 'achievement']
];

foreach ($posts as $post) {
    try {
        $stmt = $pdo->prepare("INSERT INTO posts (author_id, content, post_type) VALUES (?, ?, ?)");
        $stmt->execute($post);
        echo "Created Post: " . substr($post[1], 0, 30) . "...\n";
    } catch (Exception $e) { echo "Error creating post: " . $e->getMessage() . "\n"; }
}

echo "--- Seeding Complete ---\n";

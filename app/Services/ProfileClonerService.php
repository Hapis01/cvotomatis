<?php

namespace App\Services;

use App\Core\Database;
use PDO;

class ProfileClonerService
{
    public static function cloneProfile($sourceProfileId, $userId, $newTitle = null, $targetVacancy = null)
    {
        $db = Database::getInstance()->getConnection();
        $db->beginTransaction();

        try {
            // 1. Ambil data profil sumber
            $stmt = $db->prepare("SELECT * FROM candidate_profiles WHERE id = ? AND user_id = ?");
            $stmt->execute([$sourceProfileId, $userId]);
            $source = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$source) {
                throw new \Exception("Profil sumber tidak ditemukan atau akses tidak diizinkan.");
            }

            // Tentukan judul baru
            $profTitle = $newTitle ?: $source['professional_title'];
            if (!$newTitle && !$targetVacancy) {
                $profTitle = '[Copy] ' . $profTitle;
            }

            // 2. Insert candidate_profiles baru
            $insProf = $db->prepare("INSERT INTO candidate_profiles 
                (user_id, parent_id, full_name, professional_title, address, city, province, phone, email, portfolio_url, linkedin_url, github_url, professional_summary, photo_path, target_vacancy)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $insProf->execute([
                $userId,
                $source['parent_id'] ?: $source['id'], // Jika sumber sudah sub-profile, tetap rujuk ke master aslinya
                $source['full_name'],
                $profTitle,
                $source['address'],
                $source['city'],
                $source['province'],
                $source['phone'],
                $source['email'],
                $source['portfolio_url'],
                $source['linkedin_url'],
                $source['github_url'],
                $source['professional_summary'],
                $source['photo_path'],
                $targetVacancy
            ]);

            $newProfileId = $db->lastInsertId();

            // 3. Clone Educations
            $stmtEdu = $db->prepare("SELECT * FROM educations WHERE profile_id = ?");
            $stmtEdu->execute([$sourceProfileId]);
            $edus = $stmtEdu->fetchAll(PDO::FETCH_ASSOC);

            $insEdu = $db->prepare("INSERT INTO educations 
                (user_id, profile_id, institution, degree, major, city, start_date, end_date, gpa, accreditation, description, order_num)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            foreach ($edus as $edu) {
                $insEdu->execute([
                    $userId, $newProfileId, $edu['institution'], $edu['degree'], $edu['major'],
                    $edu['city'], $edu['start_date'], $edu['end_date'], $edu['gpa'],
                    $edu['accreditation'] ?? null, $edu['description'], $edu['order_num']
                ]);
            }

            // 4. Clone Experiences & Experience Bullets
            $stmtExp = $db->prepare("SELECT * FROM experiences WHERE profile_id = ? ORDER BY order_num ASC, id ASC");
            $stmtExp->execute([$sourceProfileId]);
            $exps = $stmtExp->fetchAll(PDO::FETCH_ASSOC);

            $insExp = $db->prepare("INSERT INTO experiences 
                (user_id, profile_id, job_title, company, location, start_date, end_date, current_job, description, order_num)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $insExpBullet = $db->prepare("INSERT INTO experience_bullets (experience_id, bullet_text, order_num) VALUES (?, ?, ?)");

            foreach ($exps as $exp) {
                $insExp->execute([
                    $userId, $newProfileId, $exp['job_title'], $exp['company'], $exp['location'],
                    $exp['start_date'], $exp['end_date'], $exp['current_job'], $exp['description'], $exp['order_num']
                ]);
                $newExpId = $db->lastInsertId();

                $stmtBullets = $db->prepare("SELECT * FROM experience_bullets WHERE experience_id = ? ORDER BY order_num ASC, id ASC");
                $stmtBullets->execute([$exp['id']]);
                $bullets = $stmtBullets->fetchAll(PDO::FETCH_ASSOC);

                foreach ($bullets as $b) {
                    $insExpBullet->execute([$newExpId, $b['bullet_text'], $b['order_num']]);
                }
            }

            // 5. Clone Projects & Project Bullets
            $stmtProj = $db->prepare("SELECT * FROM projects WHERE profile_id = ? ORDER BY order_num ASC, id ASC");
            $stmtProj->execute([$sourceProfileId]);
            $projs = $stmtProj->fetchAll(PDO::FETCH_ASSOC);

            $insProj = $db->prepare("INSERT INTO projects 
                (user_id, profile_id, project_name, role, organization_company, start_date, end_date, project_url, description, technologies, order_num)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $insProjBullet = $db->prepare("INSERT INTO project_bullets (project_id, bullet_text, order_num) VALUES (?, ?, ?)");

            foreach ($projs as $proj) {
                $insProj->execute([
                    $userId, $newProfileId, $proj['project_name'], $proj['role'], $proj['organization_company'],
                    $proj['start_date'], $proj['end_date'], $proj['project_url'], $proj['description'], $proj['technologies'], $proj['order_num']
                ]);
                $newProjId = $db->lastInsertId();

                $stmtPBullets = $db->prepare("SELECT * FROM project_bullets WHERE project_id = ? ORDER BY order_num ASC, id ASC");
                $stmtPBullets->execute([$proj['id']]);
                $pBullets = $stmtPBullets->fetchAll(PDO::FETCH_ASSOC);

                foreach ($pBullets as $pb) {
                    $insProjBullet->execute([$newProjId, $pb['bullet_text'], $pb['order_num']]);
                }
            }

            // 6. Clone Skill Categories & Skills
            $stmtCat = $db->prepare("SELECT * FROM skill_categories WHERE profile_id = ? ORDER BY id ASC");
            $stmtCat->execute([$sourceProfileId]);
            $cats = $stmtCat->fetchAll(PDO::FETCH_ASSOC);

            $insCat = $db->prepare("INSERT INTO skill_categories (user_id, profile_id, category_name) VALUES (?, ?, ?)");
            $insSkill = $db->prepare("INSERT INTO skills (category_id, skill_name, proficiency, order_num) VALUES (?, ?, ?, ?)");

            foreach ($cats as $cat) {
                $insCat->execute([$userId, $newProfileId, $cat['category_name']]);
                $newCatId = $db->lastInsertId();

                $stmtSkills = $db->prepare("SELECT * FROM skills WHERE category_id = ? ORDER BY id ASC");
                $stmtSkills->execute([$cat['id']]);
                $skills = $stmtSkills->fetchAll(PDO::FETCH_ASSOC);

                foreach ($skills as $s) {
                    $insSkill->execute([$newCatId, $s['skill_name'], $s['proficiency'], $s['order_num']]);
                }
            }

            // 7. Clone Certifications
            $stmtCert = $db->prepare("SELECT * FROM certifications WHERE profile_id = ? ORDER BY order_num ASC, id ASC");
            $stmtCert->execute([$sourceProfileId]);
            $certs = $stmtCert->fetchAll(PDO::FETCH_ASSOC);

            $insCert = $db->prepare("INSERT INTO certifications 
                (user_id, profile_id, certification_name, issuer, issue_date, expiration_date, credential_id, credential_url, description, order_num)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            foreach ($certs as $c) {
                $insCert->execute([
                    $userId, $newProfileId, $c['certification_name'], $c['issuer'], $c['issue_date'],
                    $c['expiration_date'], $c['credential_id'], $c['credential_url'], $c['description'], $c['order_num']
                ]);
            }

            // 8. Clone Awards
            $stmtAward = $db->prepare("SELECT * FROM awards WHERE profile_id = ? ORDER BY order_num ASC, id ASC");
            $stmtAward->execute([$sourceProfileId]);
            $awards = $stmtAward->fetchAll(PDO::FETCH_ASSOC);

            $insAward = $db->prepare("INSERT INTO awards 
                (user_id, profile_id, title, issuer, date_awarded, description, order_num)
                VALUES (?, ?, ?, ?, ?, ?, ?)");

            foreach ($awards as $a) {
                $insAward->execute([
                    $userId, $newProfileId, $a['title'], $a['issuer'], $a['date_awarded'], $a['description'], $a['order_num']
                ]);
            }

            // 9. Clone Organizations & Organization Bullets
            $stmtOrg = $db->prepare("SELECT * FROM organizations WHERE profile_id = ? ORDER BY order_num ASC, id ASC");
            $stmtOrg->execute([$sourceProfileId]);
            $orgs = $stmtOrg->fetchAll(PDO::FETCH_ASSOC);

            $insOrg = $db->prepare("INSERT INTO organizations 
                (user_id, profile_id, organization_name, position, division, location, start_date, end_date, description, order_num)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $insOrgBullet = $db->prepare("INSERT INTO organization_bullets (organization_id, bullet_text, order_num) VALUES (?, ?, ?)");

            foreach ($orgs as $org) {
                $insOrg->execute([
                    $userId, $newProfileId, $org['organization_name'], $org['position'], $org['division'],
                    $org['location'], $org['start_date'], $org['end_date'], $org['description'], $org['order_num']
                ]);
                $newOrgId = $db->lastInsertId();

                $stmtOBullets = $db->prepare("SELECT * FROM organization_bullets WHERE organization_id = ? ORDER BY order_num ASC, id ASC");
                $stmtOBullets->execute([$org['id']]);
                $oBullets = $stmtOBullets->fetchAll(PDO::FETCH_ASSOC);

                foreach ($oBullets as $ob) {
                    $insOrgBullet->execute([$newOrgId, $ob['bullet_text'], $ob['order_num']]);
                }
            }

            // 10. Clone Custom Sections if any
            $stmtSec = $db->prepare("SELECT * FROM custom_sections WHERE profile_id = ?");
            $stmtSec->execute([$sourceProfileId]);
            $secs = $stmtSec->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($secs)) {
                $insSec = $db->prepare("INSERT INTO custom_sections (profile_id, section_title, order_num) VALUES (?, ?, ?)");
                $insSecItem = $db->prepare("INSERT INTO custom_section_items (custom_section_id, title, subtitle, date_range, description, order_num) VALUES (?, ?, ?, ?, ?, ?)");

                foreach ($secs as $sec) {
                    $insSec->execute([$newProfileId, $sec['section_title'], $sec['order_num']]);
                    $newSecId = $db->lastInsertId();

                    $stmtItems = $db->prepare("SELECT * FROM custom_section_items WHERE custom_section_id = ?");
                    $stmtItems->execute([$sec['id']]);
                    $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($items as $item) {
                        $insSecItem->execute([
                            $newSecId, $item['title'], $item['subtitle'], $item['date_range'], $item['description'], $item['order_num']
                        ]);
                    }
                }
            }

            $db->commit();
            return $newProfileId;

        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
}

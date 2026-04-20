<?php
/**
 * Resume Parser - AI-Powered Resume Analysis
 * Extracts information from uploaded resumes (PDF, DOCX, TXT)
 */

class ResumeParser {
    private $resumeText;
    
    /**
     * Parse resume file and extract information
     */
    public function parseResume($filePath) {
        // Extract text from file
        $this->resumeText = $this->extractText($filePath);
        
        if (empty($this->resumeText)) {
            return ['error' => 'Could not extract text from resume'];
        }
        
        // Extract all information
        return [
            'personal' => $this->extractPersonalInfo(),
            'contact' => $this->extractContactInfo(),
            'skills' => $this->extractSkills(),
            'experience' => $this->extractExperience(),
            'education' => $this->extractEducation(),
            'summary' => $this->extractSummary()
        ];
    }
    
    /**
     * Extract text from different file formats
     */
    private function extractText($filePath) {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        switch ($extension) {
            case 'txt':
                return file_get_contents($filePath);
                
            case 'pdf':
                return $this->extractFromPDF($filePath);
                
            case 'docx':
                return $this->extractFromDOCX($filePath);
                
            default:
                return '';
        }
    }
    
    /**
     * Extract text from PDF
     */
    private function extractFromPDF($filePath) {
        // Using simple PDF text extraction
        // For production, use: composer require smalot/pdfparser
        
        if (class_exists('Smalot\PdfParser\Parser')) {
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($filePath);
            return $pdf->getText();
        }
        
        // Fallback: Basic extraction
        $content = file_get_contents($filePath);
        $text = '';
        
        // Simple text extraction from PDF
        if (preg_match_all('/\((.*?)\)/s', $content, $matches)) {
            $text = implode(' ', $matches[1]);
        }
        
        return $text;
    }
    
    /**
     * Extract text from DOCX
     */
    private function extractFromDOCX($filePath) {
        $zip = new ZipArchive();
        $text = '';
        
        if ($zip->open($filePath) === true) {
            $xml = $zip->getFromName('word/document.xml');
            $zip->close();
            
            if ($xml) {
                $xml = simplexml_load_string($xml);
                $text = strip_tags($xml->asXML());
            }
        }
        
        return $text;
    }
    
    /**
     * Extract personal information
     */
    private function extractPersonalInfo() {
        $info = [];
        
        // Extract name (usually first line or after "Name:")
        if (preg_match('/^([A-Z][a-z]+\s+[A-Z][a-z]+)/m', $this->resumeText, $matches)) {
            $nameParts = explode(' ', $matches[1]);
            $info['firstName'] = $nameParts[0] ?? '';
            $info['lastName'] = $nameParts[1] ?? '';
        }
        
        // Alternative: Look for "Name:" pattern
        if (empty($info) && preg_match('/Name[:\s]+([A-Z][a-z]+\s+[A-Z][a-z]+)/i', $this->resumeText, $matches)) {
            $nameParts = explode(' ', $matches[1]);
            $info['firstName'] = $nameParts[0] ?? '';
            $info['lastName'] = $nameParts[1] ?? '';
        }
        
        return $info;
    }
    
    /**
     * Extract contact information
     */
    private function extractContactInfo() {
        $contact = [];
        
        // Extract email
        if (preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $this->resumeText, $matches)) {
            $contact['email'] = $matches[0];
        }
        
        // Extract phone (Philippine format)
        if (preg_match('/(\+63|0)\s*\d{3}\s*\d{3}\s*\d{4}/', $this->resumeText, $matches)) {
            $contact['phone'] = preg_replace('/\s+/', '', $matches[0]);
        }
        
        // Extract address
        if (preg_match('/Address[:\s]+(.+?)(?:\n|Email|Phone)/is', $this->resumeText, $matches)) {
            $contact['address'] = trim($matches[1]);
        }
        
        return $contact;
    }
    
    /**
     * Extract skills
     */
    private function extractSkills() {
        $skills = [];
        
        // Common skill keywords
        $skillKeywords = [
            'php', 'javascript', 'python', 'java', 'react', 'vue', 'angular',
            'sql', 'mysql', 'postgresql', 'mongodb', 'aws', 'azure', 'docker',
            'html', 'css', 'node.js', 'express', 'laravel', 'symfony',
            'communication', 'leadership', 'teamwork', 'problem solving',
            'excel', 'powerpoint', 'word', 'customer service', 'sales',
            'marketing', 'accounting', 'nursing', 'teaching', 'management'
        ];
        
        $text = strtolower($this->resumeText);
        
        foreach ($skillKeywords as $skill) {
            if (strpos($text, $skill) !== false) {
                $skills[] = $skill;
            }
        }
        
        // Look for skills section
        if (preg_match('/Skills[:\s]+(.+?)(?:\n\n|Experience|Education)/is', $this->resumeText, $matches)) {
            $skillsText = $matches[1];
            // Extract comma or bullet separated skills
            $extractedSkills = preg_split('/[,•\n]/', $skillsText);
            foreach ($extractedSkills as $skill) {
                $skill = trim($skill);
                if (!empty($skill) && strlen($skill) < 50) {
                    $skills[] = strtolower($skill);
                }
            }
        }
        
        return array_unique($skills);
    }
    
    /**
     * Extract work experience
     */
    private function extractExperience() {
        $experience = [];
        
        // Calculate years of experience
        $years = 0;
        if (preg_match_all('/(\d{4})\s*[-–]\s*(\d{4}|Present|Current)/i', $this->resumeText, $matches)) {
            foreach ($matches[1] as $index => $startYear) {
                $endYear = $matches[2][$index];
                if (strtolower($endYear) === 'present' || strtolower($endYear) === 'current') {
                    $endYear = date('Y');
                }
                $years += (int)$endYear - (int)$startYear;
            }
        }
        
        $experience['years'] = $years;
        
        // Extract job titles
        $jobTitles = [];
        $commonTitles = [
            'developer', 'engineer', 'manager', 'analyst', 'designer',
            'consultant', 'specialist', 'coordinator', 'assistant', 'director'
        ];
        
        foreach ($commonTitles as $title) {
            if (preg_match_all('/([A-Z][a-z]+\s+)*' . $title . '/i', $this->resumeText, $matches)) {
                $jobTitles = array_merge($jobTitles, $matches[0]);
            }
        }
        
        $experience['titles'] = array_unique($jobTitles);
        
        return $experience;
    }
    
    /**
     * Extract education
     */
    private function extractEducation() {
        $education = [];
        
        // Education levels
        $levels = [
            'doctorate' => ['phd', 'doctorate', 'doctoral'],
            'master' => ['master', 'mba', 'ms', 'ma'],
            'bachelor' => ['bachelor', 'bs', 'ba', 'bsc'],
            'associate' => ['associate'],
            'vocational' => ['vocational', 'certificate', 'diploma']
        ];
        
        $text = strtolower($this->resumeText);
        
        foreach ($levels as $level => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($text, $keyword) !== false) {
                    $education['level'] = $level;
                    break 2;
                }
            }
        }
        
        // Extract degree
        if (preg_match('/(?:Bachelor|Master|PhD|Doctorate)\s+(?:of\s+)?([A-Za-z\s]+)/i', $this->resumeText, $matches)) {
            $education['degree'] = trim($matches[0]);
        }
        
        return $education;
    }
    
    /**
     * Extract professional summary
     */
    private function extractSummary() {
        // Look for summary/objective section
        if (preg_match('/(?:Summary|Objective|Profile)[:\s]+(.+?)(?:\n\n|Experience|Skills)/is', $this->resumeText, $matches)) {
            return trim($matches[1]);
        }
        
        // Fallback: First paragraph
        $lines = explode("\n", $this->resumeText);
        $summary = '';
        foreach ($lines as $line) {
            $line = trim($line);
            if (strlen($line) > 50) {
                $summary = $line;
                break;
            }
        }
        
        return $summary;
    }
    
    /**
     * Auto-fill application form data
     */
    public function getFormData($parsedData) {
        return [
            'firstName' => $parsedData['personal']['firstName'] ?? '',
            'lastName' => $parsedData['personal']['lastName'] ?? '',
            'email' => $parsedData['contact']['email'] ?? '',
            'phone' => $parsedData['contact']['phone'] ?? '',
            'address' => $parsedData['contact']['address'] ?? '',
            'skills' => implode(', ', $parsedData['skills'] ?? []),
            'experience' => $parsedData['experience']['years'] ?? 0,
            'education' => $parsedData['education']['level'] ?? '',
            'coverLetter' => $parsedData['summary'] ?? ''
        ];
    }
}
?>

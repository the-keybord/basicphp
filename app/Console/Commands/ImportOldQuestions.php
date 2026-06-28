<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Question;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ImportOldQuestions extends Command
{
    protected $signature = 'app:import-old-questions';
    protected $description = 'Import all questions from old_project/public_html/app/questions/';

    public function handle()
    {
        $oldQuestionsPath = base_path('old_project/public_html/app/questions');

        if (!File::isDirectory($oldQuestionsPath)) {
            $this->error("Old questions path not found: {$oldQuestionsPath}");
            return 1;
        }

        // Clean up previously imported questions to avoid duplication and update structures cleanly
        Question::where('id', '>=', 39)->delete();

        $directories = File::directories($oldQuestionsPath);
        $this->info("Found " . count($directories) . " question directories.");

        // Fetch subcategories dynamically to match auto-incremented IDs safely
        $subcategories = [
            'design' => DB::table('subcategories')->where('name', 'Database design')->value('id') ?? 1,
            'retrieval' => DB::table('subcategories')->where('name', 'Data retrieval')->value('id') ?? 2,
            'management' => DB::table('subcategories')->where('name', 'Database object management')->value('id') ?? 3,
            'manipulation' => DB::table('subcategories')->where('name', 'Data manipulation')->value('id') ?? 4,
            'trouble' => DB::table('subcategories')->where('name', 'Troubleshooting')->value('id') ?? 5,
        ];

        $imported = 0;

        foreach ($directories as $dir) {
            $questionId = basename($dir);
            $questionPhpFile = $dir . '/question.php';

            if (!File::exists($questionPhpFile)) {
                $this->warn("question.php not found in directory: {$dir}");
                continue;
            }

            $html = File::get($questionPhpFile);

            // 1. Determine the question type by parsing driver
            $type = 'singleselect';
            if (str_contains($html, 'radio_driver.php')) {
                $type = 'singleselect';
            } elseif (str_contains($html, 'true_driver.php')) {
                $type = 'truefalse';
            } elseif (str_contains($html, 'down_driver.php')) {
                $type = 'dropdown';
            } elseif (str_contains($html, 'drag_driver.php')) {
                $type = 'drag_and_drop';
            } elseif (str_contains($html, 'multiple_choice_driver.php')) {
                $type = 'multiselect';
            }

            // 2. Pre-process drag-and-drop tiles from bracket expressions
            $options = [];
            $subjects = [];

            if ($type === 'drag_and_drop') {
                if (preg_match('/{{{(.+?)}}}/s', $html, $ddMatches)) {
                    $poolText = $ddMatches[1];
                    // Strip optional code wrappers
                    $poolText = preg_replace('/<\/?code>/i', '', $poolText);
                    $options = array_map('trim', explode('|||', $poolText));
                    // Remove tiles list bracket block from input
                    $html = str_replace($ddMatches[0], '', $html);
                }
            }

            // 3. Extract question text & image
            // Look for <div class='question'>...</div>
            preg_match('/<div class=[\'"]question[\'"]>(.+?)<\/div>/s', $html, $qMatch);
            $questionHtml = $qMatch[1] ?? '';
            
            // Clean up empty tags and <p></p>
            $questionHtml = preg_replace('/<p>\s*<\/p>/i', '', $questionHtml);
            
            // Look for <img> tags
            $imagePath = '';
            if (preg_match('/<img[^>]+src=[\'"](.+?)[\'"]/i', $questionHtml, $imgMatch)) {
                $imageSrc = $imgMatch[1];
                $filename = basename(urldecode($imageSrc));
                $localImageFile = $dir . '/' . $filename;

                if (File::exists($localImageFile)) {
                    $destDir = public_path("questions/{$questionId}");
                    File::ensureDirectoryExists($destDir);
                    File::copy($localImageFile, $destDir . '/' . $filename);
                    $imagePath = "questions/{$questionId}/{$filename}";
                } else {
                    // Try to copy any PNG/JPG file in the directory if filename didn't match exactly
                    $files = File::files($dir);
                    foreach ($files as $file) {
                        $ext = strtolower($file->getExtension());
                        if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'svg'])) {
                            $destDir = public_path("questions/{$questionId}");
                            File::ensureDirectoryExists($destDir);
                            File::copy($file->getPathname(), $destDir . '/' . $file->getFilename());
                            $imagePath = "questions/{$questionId}/" . $file->getFilename();
                            break;
                        }
                    }
                }
                
                // Remove the <img> tag from text
                $questionHtml = preg_replace('/<img[^>]+>/i', '', $questionHtml);
            }

            // Extract plain text from <pre> if it exists
            $text = '';
            if (preg_match('/<pre>(.+?)<\/pre>/s', $questionHtml, $preMatch)) {
                $text = trim($preMatch[1]);
            } else {
                $text = trim(strip_tags($questionHtml, '<br>'));
                $text = str_replace('<br>', "\n", $text);
                $text = str_replace('<br />', "\n", $text);
            }
            $text = htmlspecialchars_decode($text, ENT_QUOTES);

            // 4. Parse options/subjects for standard questions
            if ($type !== 'drag_and_drop') {
                preg_match_all('/<div class=[\'"]option[\'"]>(.+?)<\/div>/s', $html, $optMatches);
                $rawOptions = $optMatches[1] ?? [];

                foreach ($rawOptions as $opt) {
                    $cleanedOpt = preg_replace('/<\/?code>/i', '', $opt);
                    $cleanedOpt = str_replace(['<br />', '<br>', '<br/>'], "\n", $cleanedOpt);
                    $cleanedOpt = htmlspecialchars_decode(trim($cleanedOpt), ENT_QUOTES);
                    $options[] = $cleanedOpt;
                }
            }

            // Handle specific type conversions
            if ($type === 'truefalse') {
                // In old questions, the statements are options. We convert them to subjects.
                $subjects = $options;
                $options = [];
                
                // Guess correct answer
                $guessArray = array_fill(0, count($subjects), 'Yes');
                $guess = implode(', ', $guessArray);
            } elseif ($type === 'dropdown') {
                // Dropdown options are inside {{{...}}} in the text
                // Find all {{{...}}}
                preg_match_all('/{{{(.+?)}}}/s', $text, $ddMatches);
                $dropdowns = $ddMatches[1] ?? [];
                
                $subjects = [];
                $guessParts = [];
                foreach ($dropdowns as $dd) {
                    $parts = array_map('trim', explode('|||', $dd));
                    $subjects[] = $parts;
                    $guessParts[] = $parts[0] ?? '';
                }
                
                // Replace {{{...}}} in text with __
                $text = preg_replace('/{{{(.+?)}}}/s', '__', $text);
                
                // Guess correct answer
                $guess = implode(', ', $guessParts);
            } elseif ($type === 'drag_and_drop') {
                // Normalize legacy blanks "___" to double underscores "__" for S3 Drag Engine compatibility
                $text = preg_replace('/___+/i', '__', $text);
                
                // Guess: first 2 options or just comma-separated options
                $guess = implode(', ', array_slice($options, 0, 2));
            } elseif ($type === 'multiselect') {
                // Guess: indices 1, 2
                $guess = '1';
            } else { // singleselect
                // Guess: first option
                $guess = $options[0] ?? '';
            }

            // 5. Construct XML representation
            $xml = "<question>\n";
            $xml .= "<text>" . htmlspecialchars($text, ENT_QUOTES) . "</text>\n";
            if (!empty($imagePath)) {
                $xml .= "<image>" . htmlspecialchars($imagePath, ENT_QUOTES) . "</image>\n";
            } else {
                $xml .= "<image></image>\n";
            }

            if (!empty($options)) {
                $xml .= "<options>\n";
                foreach ($options as $opt) {
                    $xml .= "<option>" . htmlspecialchars($opt, ENT_QUOTES) . "</option>\n";
                }
                $xml .= "</options>\n";
            }

            if (!empty($subjects)) {
                $xml .= "<subjects>\n";
                foreach ($subjects as $sub) {
                    if (is_array($sub)) {
                        $xml .= "<subject>\n";
                        foreach ($sub as $opt) {
                            $xml .= "<option>" . htmlspecialchars($opt, ENT_QUOTES) . "</option>\n";
                        }
                        $xml .= "</subject>\n";
                    } else {
                        $xml .= "<subject>" . htmlspecialchars($sub, ENT_QUOTES) . "</subject>\n";
                    }
                }
                $xml .= "</subjects>\n";
            }
            $xml .= "</question>";

            // 6. Guess subcategory based on keyword analysis
            $subId = $subcategories['design']; // Default
            $textLower = strtolower($text);
            
            if (str_contains($textLower, 'select') || str_contains($textLower, 'from') || str_contains($textLower, 'where') || str_contains($textLower, 'join') || str_contains($textLower, 'union') || str_contains($textLower, 'intersect') || str_contains($textLower, 'group by')) {
                $subId = $subcategories['retrieval']; // Data retrieval
            } elseif (str_contains($textLower, 'insert') || str_contains($textLower, 'update') || str_contains($textLower, 'delete')) {
                $subId = $subcategories['manipulation']; // Data manipulation
            } elseif (str_contains($textLower, 'create table') || str_contains($textLower, 'drop table') || str_contains($textLower, 'alter table') || str_contains($textLower, 'stored procedure') || str_contains($textLower, 'index')) {
                $subId = $subcategories['management']; // Database object management
            } elseif (str_contains($textLower, 'backup') || str_contains($textLower, 'restore') || str_contains($textLower, 'transaction log') || str_contains($textLower, 'error') || str_contains($textLower, 'failed')) {
                $subId = $subcategories['trouble']; // Troubleshooting
            } elseif (str_contains($textLower, '3nf') || str_contains($textLower, 'normal form') || str_contains($textLower, 'primary key') || str_contains($textLower, 'foreign key') || str_contains($textLower, 'composite key') || str_contains($textLower, 'relationship')) {
                $subId = $subcategories['design']; // Database design
            }

            // 7. Save/Insert into Question database
            Question::create([
                'primary_subcategory_id' => $subId,
                'question_type' => $type,
                'xml_content' => $xml,
                'correct_answer_string' => $guess,
            ]);

            $imported++;
        }

        $this->info("Successfully imported {$imported} questions into the database!");
        return 0;
    }
}

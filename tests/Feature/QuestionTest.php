<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Question;
use App\Services\QuestionEngine\QuestionParser;
use App\Services\QuestionEngine\QuestionRenderer;

class QuestionTest extends TestCase
{
    /**
     * Test that all seeded questions parse and render successfully.
     */
    public function test_all_seeded_questions_parse_and_render_successfully(): void
    {
        $parser = new QuestionParser();
        $renderer = new QuestionRenderer();

        $questions = Question::all();
        $this->assertNotEmpty($questions, "No questions found in the database. Did you run the seeder?");

        foreach ($questions as $q) {
            try {
                $parsed = $parser->parse($q->xml_content);
                $rendered = $renderer->render($parsed);
                
                $this->assertIsArray($parsed);
                $this->assertIsString($parsed['text']);
                
                // If it is a singleselect or multiselect, options should not be empty
                if (in_array($q->question_type, ['singleselect', 'multiselect', 'dropdown'])) {
                    if (empty($parsed['subjects'])) {
                        $this->assertNotEmpty($parsed['options'], "Question ID {$q->id} options are empty");
                    } else {
                        $this->assertNotEmpty($parsed['subjects'], "Question ID {$q->id} subjects are empty");
                    }
                }
            } catch (\Exception $e) {
                $this->fail("Question ID {$q->id} failed to parse/render. Error: " . $e->getMessage() . "\nXML: " . $q->xml_content);
            }
        }
    }
}

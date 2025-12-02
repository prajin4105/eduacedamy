<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReviewSeeder extends Seeder
{
    private array $positiveAdjectives = [
        'Excellent', 'Amazing', 'Fantastic', 'Outstanding', 'Brilliant', 'Superb',
        'Wonderful', 'Great', 'Terrific', 'Awesome', 'Impressive', 'Exceptional',
        'Phenomenal', 'Incredible', 'Perfect', 'Tremendous', 'Marvelous', 'Splendid',
        'Fabulous', 'Stellar', 'First-class', 'Top-notch', 'Remarkable', 'Superior'
    ];

    private array $negativeAdjectives = [
        'Disappointing', 'Poor', 'Mediocre', 'Subpar', 'Underwhelming', 'Lackluster',
        'Uninspiring', 'Frustrating', 'Confusing', 'Outdated', 'Overpriced', 'Basic',
        'Unorganized', 'Boring', 'Tedious', 'Frustrating', 'Incomplete', 'Mismarketed'
    ];

    private array $reviewTemplates = [
        'positive' => [
            "This course was absolutely [adjective]! The instructor explains concepts clearly and provides practical examples.",
            "I learned so much from this [adjective] course. The content is well-structured and easy to follow.",
            "[Adjective] course! The instructor's teaching style made complex topics easy to understand.",
            "One of the best courses I've taken. The [adjective] content and real-world examples were incredibly valuable.",
            "[Adjective] instruction and comprehensive materials. I feel much more confident in this subject now.",
            "The course was [adjective] and exceeded my expectations. The practical exercises were particularly helpful.",
            "I would highly recommend this [adjective] course to anyone looking to learn this material.",
            "[Adjective] content delivered by a knowledgeable instructor. Worth every penny!"
        ],
        'negative' => [
            "I was [adjective] with this course. The content felt outdated and not very practical.",
            "The course was [adjective] and didn't meet my expectations. The material could be better organized.",
            "[Adjective] course. The instructor's explanations were unclear at times.",
            "I was hoping for more from this [adjective] course. The content felt too basic.",
            "The course was [adjective] and didn't provide enough hands-on practice.",
            "[Adjective] instruction. The course could benefit from more real-world examples.",
            "I wouldn't recommend this [adjective] course. The material wasn't well-explained.",
            "The course was [adjective] and didn't cover the topics in enough depth."
        ],
        'neutral' => [
            "This was a [adjective] course overall. Some parts were better than others.",
            "The course was [adjective]. It covered the basics but could go deeper into some topics.",
            "[Adjective] content. I learned some useful information but expected more advanced material.",
            "The course was [adjective]. The instructor was knowledgeable but the pace was a bit slow.",
            "[Adjective] course. It provided a good foundation but could use more practical examples.",
            "The material was [adjective]. Some sections were more helpful than others.",
            "This [adjective] course met my basic expectations but didn't exceed them.",
            "The course was [adjective]. The content was good but the presentation could be improved."
        ]
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $completedEnrollments = Enrollment::where('status', 'completed')
            ->with(['user', 'course'])
            ->get();
        
        $reviews = [];
        $now = now();
        
        foreach ($completedEnrollments as $enrollment) {
            // Only create a review for a percentage of completed courses (60-80%)
            if (rand(1, 100) > rand(60, 80)) {
                continue;
            }
            
            $rating = $this->generateRating($enrollment->course);
            $reviewType = $this->getReviewType($rating);
            $adjective = $this->getAdjective($reviewType);
            
            $reviewTemplate = $this->reviewTemplates[$reviewType][array_rand($this->reviewTemplates[$reviewType])];
            $reviewText = str_replace(['[adjective]', '[Adjective]'], [strtolower($adjective), $adjective], $reviewTemplate);
            
            // Add more specific feedback based on rating
            $reviewText .= $this->getAdditionalFeedback($rating);
            
            $reviews[] = [
                'user_id' => $enrollment->user_id,
                'course_id' => $enrollment->course_id,
                'rating' => $rating,
                'comment' => $reviewText,
                'created_at' => $this->getReviewDate($enrollment->completed_at),
                'updated_at' => $now,
            ];
            
            // Insert in chunks to manage memory
            if (count($reviews) >= 500) {
                DB::table('reviews')->insert($reviews);
                $reviews = [];
            }
        }
        
        // Insert any remaining reviews
        if (!empty($reviews)) {
            DB::table('reviews')->insert($reviews);
        }
        
        // Update course ratings
        $this->updateCourseRatings();
    }
    
    private function generateRating(Course $course): int
    {
        // Base rating with some randomness
        $baseRating = $this->getBaseCourseRating($course);
        
        // Add some randomness (-1 to +1)
        $randomAdjustment = rand(-10, 10) / 10;
        $rating = $baseRating + $randomAdjustment;
        
        // Ensure rating is between 1 and 5
        return max(1, min(5, round($rating)));
    }
    
    private function getBaseCourseRating(Course $course): float
    {
        // Base rating on course quality (this is simplified)
        $rating = 3.5; // Average base rating
        
        // Adjust based on course attributes
        if ($course->level === 'beginner') {
            $rating += 0.2; // Beginner courses often get slightly higher ratings
        } elseif ($course->level === 'advanced') {
            $rating -= 0.1; // Advanced courses might be more polarizing
        }
        
        // Adjust for course duration (not too short, not too long)
        $hours = $course->duration_in_minutes / 60;
        if ($hours >= 2 && $hours <= 10) {
            $rating += 0.2;
        } elseif ($hours > 20) {
            $rating -= 0.1;
        }
        
        return $rating;
    }
    
    private function getReviewType(int $rating): string
    {
        if ($rating >= 4) {
            return 'positive';
        } elseif ($rating <= 2) {
            return 'negative';
        } else {
            return 'neutral';
        }
    }
    
    private function getAdjective(string $reviewType): string
    {
        if ($reviewType === 'positive') {
            return $this->positiveAdjectives[array_rand($this->positiveAdjectives)];
        } elseif ($reviewType === 'negative') {
            return $this->negativeAdjectives[array_rand($this->negativeAdjectives)];
        } else {
            // Neutral adjectives can be from either list, but less extreme
            $allAdjectives = array_merge(
                array_map(fn($adj) => strtolower($adj), $this->positiveAdjectives),
                $this->negativeAdjectives
            );
            return $allAdjectives[array_rand($allAdjectives)];
        }
    }
    
    private function getAdditionalFeedback(int $rating): string
    {
        $feedback = [];
        
        // Add specific feedback based on rating
        if ($rating >= 4) {
            $positives = [
                " The instructor's teaching style was engaging and easy to follow.",
                " The course materials were comprehensive and well-organized.",
                " The practical exercises helped reinforce the concepts.",
                " I appreciated the real-world examples provided throughout the course.",
                " The instructor was very responsive to questions in the Q&A section.",
                " The course content was up-to-date and relevant.",
                " I was able to apply what I learned immediately to my work.",
                " The course exceeded my expectations in terms of content quality."
            ];
            $feedback[] = $positives[array_rand($positives)];
            
            // Add a second positive for 5-star reviews
            if ($rating === 5 && rand(1, 2) === 1) {
                $feedback[] = $positives[array_rand($positives)];
            }
        } elseif ($rating <= 2) {
            $negatives = [
                " The course materials could be more comprehensive.",
                " Some of the explanations were unclear and left me with questions.",
                " The course content felt outdated in some areas.",
                " The instructor's teaching style didn't work well for me.",
                " The course could benefit from more practical examples.",
                " The pacing was either too fast or too slow at times.",
                " The audio/video quality could be improved.",
                " The course didn't cover some topics in enough depth."
            ];
            $feedback[] = $negatives[array_rand($negatives)];
            
            // Add a second negative for 1-star reviews
            if ($rating === 1 && rand(1, 2) === 1) {
                $feedback[] = $negatives[array_rand($negatives)];
            }
        } else {
            // Neutral feedback for 3-star reviews
            $neutrals = [
                " The course had its strengths and weaknesses.",
                " Some sections were better than others.",
                " It was an okay course overall, but there's room for improvement.",
                " The course met my basic expectations but didn't exceed them.",
                " I learned some useful information, but the course could be better.",
                " The content was decent but could use some updates.",
                " The instructor was knowledgeable but the course structure could be improved.",
                " It was a fair introduction to the topic but lacked depth in some areas."
            ];
            $feedback[] = $neutrals[array_rand($neutrals)];
        }
        
        // Add a suggestion for improvement (even for positive reviews)
        if ($rating < 5) {
            $suggestions = [
                " I would have liked to see more examples of ".$this->getRandomTopic().".",
                " Adding more exercises on ".$this->getRandomTopic()." would be beneficial.",
                " The section on ".$this->getRandomTopic()." could be expanded.",
                " More real-world applications of ".$this->getRandomTopic()." would be helpful.",
                " The course could benefit from additional resources on ".$this->getRandomTopic()."."
            ];
            $feedback[] = $suggestions[array_rand($suggestions)];
        }
        
        return implode('', $feedback);
    }
    
    private function getRandomTopic(): string
    {
        $topics = [
            'advanced techniques', 'troubleshooting', 'best practices', 'performance optimization',
            'debugging', 'error handling', 'security considerations', 'scalability',
            'testing strategies', 'deployment options', 'integration with other tools',
            'real-world case studies', 'industry standards', 'emerging trends'
        ];
        return $topics[array_rand($topics)];
    }
    
    private function getReviewDate(Carbon $completionDate): Carbon
    {
        // Reviews are typically left within a few days to a few weeks after completion
        $daysAfterCompletion = rand(0, 30);
        return $completionDate->copy()->addDays($daysAfterCompletion);
    }
    
    private function updateCourseRatings(): void
    {
        // Update the average rating and review count for each course
        $courses = Course::has('reviews')->withCount('reviews')->get();
        
        foreach ($courses as $course) {
            $ratingData = DB::table('reviews')
                ->where('course_id', $course->id)
                ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as review_count')
                ->first();
            
            $course->update([
                'average_rating' => round($ratingData->avg_rating, 1),
                'review_count' => $ratingData->review_count,
            ]);
        }
    }
}

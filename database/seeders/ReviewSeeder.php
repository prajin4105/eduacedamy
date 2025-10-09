<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Models\Review;
use App\Models\Enrollment;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $reviews = [
            // 5-star reviews
            [
                'rating' => 5,
                'comment' => 'Absolutely outstanding course! The instructor is incredibly knowledgeable and explains everything in a way that\'s easy to understand. I\'ve learned so much and can already apply these skills in my work. Highly recommended!'
            ],
            [
                'rating' => 5,
                'comment' => 'This course is a game-changer! Best investment I\'ve made for my career. The content is comprehensive, well-organized, and delivered with passion. Can\'t wait to take more courses from this instructor.'
            ],
            [
                'rating' => 5,
                'comment' => 'Exceptional quality! The lessons are clear, engaging, and practical. I appreciate the real-world examples that help solidify the concepts. Five stars all the way!'
            ],
            [
                'rating' => 5,
                'comment' => 'Perfect course for learning this subject! The instructor breaks down complex topics into digestible pieces. Definitely worth every penny.'
            ],
            [
                'rating' => 5,
                'comment' => 'Outstanding instructor and excellent course material. The teaching method is very effective and the content is relevant to current industry standards.'
            ],
            [
                'rating' => 5,
                'comment' => 'Incredible learning experience! This course exceeded all my expectations. The instructor is engaging and the material is practical and immediately applicable.'
            ],
            [
                'rating' => 5,
                'comment' => 'One of the best courses I\'ve taken. The quality of instruction is top-notch and the content is very relevant. Highly recommend to everyone!'
            ],
            [
                'rating' => 5,
                'comment' => 'Fantastic course with excellent teaching! I feel confident in applying what I learned. The instructor really knows their subject matter.'
            ],
            [
                'rating' => 5,
                'comment' => 'Best money spent on education! This course provides real value and practical knowledge. The instructor is patient and thorough.'
            ],
            [
                'rating' => 5,
                'comment' => 'Absolutely love this course! Well-structured, easy to follow, and packed with useful information. Definitely recommend!'
            ],

            // 4-star reviews
            [
                'rating' => 4,
                'comment' => 'Great course overall! The content is solid and well-presented. Some sections could have more depth, but generally very good for the price.'
            ],
            [
                'rating' => 4,
                'comment' => 'Very informative and well-structured. I enjoyed the course and learned a lot. Would appreciate more practice exercises.'
            ],
            [
                'rating' => 4,
                'comment' => 'Excellent instructor with clear explanations. The course covers all the essential topics. A few more advanced examples would make it perfect.'
            ],
            [
                'rating' => 4,
                'comment' => 'Good quality content with practical examples. The pace is appropriate and the material is easy to follow. Recommend for beginners and intermediates.'
            ],
            [
                'rating' => 4,
                'comment' => 'Really enjoyed this course. The instructor is engaging and the lessons are well-organized. Would appreciate downloadable resources.'
            ],
            [
                'rating' => 4,
                'comment' => 'Solid course with great explanations. The instructor is knowledgeable and easy to understand. Some sections could be more detailed.'
            ],
            [
                'rating' => 4,
                'comment' => 'Very good course! Comprehensive content and clear instruction. Minor issues but overall very satisfied with my purchase.'
            ],
            [
                'rating' => 4,
                'comment' => 'Great learning experience! The material is relevant and well-taught. Would be perfect with more quizzes and assignments.'
            ],
            [
                'rating' => 4,
                'comment' => 'High quality instruction and content. The course is well-organized and easy to follow. Recommended for those serious about learning.'
            ],
            [
                'rating' => 4,
                'comment' => 'Enjoyed the course very much. The instructor is professional and knowledgeable. Some topics could use more elaboration.'
            ],

            // 3-star reviews
            [
                'rating' => 3,
                'comment' => 'Decent course with useful information. Some parts are well explained while others could be clearer. Average quality for the price.'
            ],
            [
                'rating' => 3,
                'comment' => 'Good content but could be improved. The pacing feels a bit rushed in some sections. Generally informative but not exceptional.'
            ],
            [
                'rating' => 3,
                'comment' => 'Satisfactory course. The material covers the basics well but lacks depth in advanced topics. It\'s okay, nothing extraordinary.'
            ],
            [
                'rating' => 3,
                'comment' => 'Average quality. Some lessons are excellent while others feel incomplete. Would benefit from more supplementary materials.'
            ],
            [
                'rating' => 3,
                'comment' => 'Decent for beginners. The instructor explains well but could include more real-world projects. Decent value for money.'
            ],
            [
                'rating' => 3,
                'comment' => 'It\'s a decent course if you\'re new to the subject. Not bad, but there are better options available elsewhere.'
            ],
            [
                'rating' => 3,
                'comment' => 'Okay course. The material is straightforward but lacks engagement. It gets the job done but nothing special.'
            ],
            [
                'rating' => 3,
                'comment' => 'Middle of the road. Some valuable content here but also some boring sections. Overall worth watching but not outstanding.'
            ],
            [
                'rating' => 3,
                'comment' => 'Standard quality course. The instructor knows what they\'re talking about but could be more engaging. Worth the time.'
            ],
            [
                'rating' => 3,
                'comment' => 'Fair course with moderate value. Some good insights but also some repetitive content. Acceptable overall.'
            ],

            // 2-star reviews
            [
                'rating' => 2,
                'comment' => 'Below expectations. While the content is relevant, the presentation could be much better. Needs improvement in pacing and clarity.'
            ],
            [
                'rating' => 2,
                'comment' => 'Somewhat disappointing. I learned a few things but expected much more for this price. The course feels incomplete and rushed.'
            ],
            [
                'rating' => 2,
                'comment' => 'Not impressed. The instructor assumes too much prior knowledge. Many topics feel underdeveloped and lacking in examples.'
            ],
            [
                'rating' => 2,
                'comment' => 'Mediocre at best. There are better alternatives available. The course could use better organization and clearer explanations.'
            ],
            [
                'rating' => 2,
                'comment' => 'Disappointed with the quality. Expected more comprehensive coverage. The material feels superficial in many areas.'
            ],
            [
                'rating' => 2,
                'comment' => 'Not worth the money. The course is boring and hard to follow. I regret purchasing this.'
            ],
            [
                'rating' => 2,
                'comment' => 'Poor quality presentation. The content is outdated and the instructor is uninspiring. Looked for a refund but didn\'t qualify.'
            ],
            [
                'rating' => 2,
                'comment' => 'Subpar course. The lessons are disorganized and the instructor rushes through important concepts.'
            ],
            [
                'rating' => 2,
                'comment' => 'Not recommended. I could only learn half of what was promised. The course lacks depth and practical examples.'
            ],
            [
                'rating' => 2,
                'comment' => 'Disappointing experience. The material is confusing and poorly structured. Expected much better quality.'
            ],

            // 1-star reviews
            [
                'rating' => 1,
                'comment' => 'Very disappointed. The course is poorly organized and hard to follow. Not worth the money. Would not recommend.'
            ],
            [
                'rating' => 1,
                'comment' => 'Waste of time and money. The content is outdated and the instructor is not engaging. Very disappointed with this purchase.'
            ],
            [
                'rating' => 1,
                'comment' => 'Terrible course. The material is confusing and incomplete. The instructor lacks clarity and passion. Definitely not recommended.'
            ],
            [
                'rating' => 1,
                'comment' => 'Awful experience. I couldn\'t understand most of the lessons. This course needs a complete overhaul. Not worth it at all.'
            ],
            [
                'rating' => 1,
                'comment' => 'Extremely disappointed. The course is poorly executed and the explanations are unclear. Would not recommend to anyone.'
            ],
            [
                'rating' => 1,
                'comment' => 'Absolute waste of money. The instructor is unprepared and the course is a mess. Worst purchase I\'ve made on this platform.'
            ],
            [
                'rating' => 1,
                'comment' => 'Terrible quality. I learned nothing from this course. The instructor needs more training in teaching. Very frustrated.'
            ],
            [
                'rating' => 1,
                'comment' => 'Horrible course! Disorganized, confusing, and completely unhelpful. I want a refund. Do not waste your time or money.'
            ],
            [
                'rating' => 1,
                'comment' => 'This is the worst course I\'ve ever taken. Nothing makes sense and the instructor is not helpful. Total disaster.'
            ],
            [
                'rating' => 1,
                'comment' => 'Complete failure. The course is outdated, badly taught, and a complete waste of money. Highly disappointed.'
            ],
        ];

        // Get all students and courses
        $students = User::where('role', 'student')->get();
        $courses = Course::where('is_published', true)->get();

        if ($students->isEmpty() || $courses->isEmpty()) {
            $this->command->warn('No students or courses found. Please run DemoDataSeeder first.');
            return;
        }

        // Create 100 reviews
        $reviewsCreated = 0;
        $maxAttempts = 500;
        $attempts = 0;

        while ($reviewsCreated < 100 && $attempts < $maxAttempts) {
            $attempts++;

            // Get random student and course
            $student = $students->random();
            $course = $courses->random();

            // Check if student has enrollment in this course
            $enrollment = Enrollment::where('user_id', $student->id)
                ->where('course_id', $course->id)
                ->first();

            // If no enrollment, create one
            if (!$enrollment) {
                $enrollment = Enrollment::create([
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                    'amount_paid' => $course->price,
                    'status' => 'completed',
                    'progress_percentage' => 100,
                    'enrolled_at' => now()->subDays(rand(1, 365)),
                ]);
            }

            // Check if review already exists
            $existingReview = Review::where('user_id', $student->id)
                ->where('course_id', $course->id)
                ->first();

            if (!$existingReview) {
                $reviewData = $reviews[$reviewsCreated % count($reviews)];

                Review::create([
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                    'rating' => $reviewData['rating'],
                    'comment' => $reviewData['comment'],
                ]);

                $reviewsCreated++;
            }
        }

        $this->command->info("Successfully created {$reviewsCreated} reviews!");
    }
}

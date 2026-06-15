<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run()
    {
        // Categories
        $categories = [];
        foreach (['Crypto Basics', 'Technical Analysis', 'Risk Management', 'Forex Trading', 'Blockchain'] as $name) {
            $categories[$name] = CourseCategory::firstOrCreate(['name' => $name])->id;
        }

        // Courses
        $coursesData = [
            [
                'title' => 'Introduction to Cryptocurrency',
                'description' => 'Learn the fundamentals of cryptocurrency, blockchain technology, and how digital assets work. Perfect for beginners looking to understand the crypto ecosystem.',
                'course_category_id' => $categories['Crypto Basics'],
                'amount' => 0,
                'image' => 'https://images.unsplash.com/photo-1639762681485-074b7f938ba0?w=600',
                'is_published' => true,
                'lessons' => [
                    ['What is Cryptocurrency?', 'Overview of digital currencies and their history', 'https://www.youtube.com/embed/1YyAzVmP9xQ', '8:30', true],
                    ['How Blockchain Works', 'Understanding distributed ledger technology', 'https://www.youtube.com/embed/SSo_EIwHSd4', '12:15', true],
                    ['Setting Up Your First Wallet', 'Step-by-step guide to crypto wallets', 'https://www.youtube.com/embed/GSTiKEyMXpo', '10:00', false],
                ],
            ],
            [
                'title' => 'Technical Analysis Masterclass',
                'description' => 'Master chart patterns, indicators, and price action strategies. Covers RSI, MACD, Bollinger Bands, Fibonacci retracements, and advanced candlestick patterns.',
                'course_category_id' => $categories['Technical Analysis'],
                'amount' => 49.99,
                'image' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=600',
                'is_published' => true,
                'lessons' => [
                    ['Reading Candlestick Charts', 'Master candlestick patterns and what they signal', 'https://www.youtube.com/embed/C3KRwfj9F7E', '15:20', true],
                    ['Support & Resistance Levels', 'Identify key price levels for entries and exits', 'https://www.youtube.com/embed/GlYsMHDfJTM', '11:45', false],
                    ['Moving Averages & MACD', 'Using lagging indicators for trend confirmation', 'https://www.youtube.com/embed/eob4rg2oYk4', '14:30', false],
                    ['Fibonacci Retracement', 'Apply Fibonacci levels to predict reversals', 'https://www.youtube.com/embed/XJkTKSn2hoo', '13:00', false],
                ],
            ],
            [
                'title' => 'Risk Management & Portfolio Strategy',
                'description' => 'Protect your capital with proven risk management techniques. Learn position sizing, stop-loss strategies, portfolio diversification, and how to manage drawdowns.',
                'course_category_id' => $categories['Risk Management'],
                'amount' => 29.99,
                'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600',
                'is_published' => true,
                'lessons' => [
                    ['Position Sizing Fundamentals', 'Calculate optimal trade sizes based on risk tolerance', 'https://www.youtube.com/embed/QgaTlTfQnfo', '9:45', true],
                    ['Stop-Loss Strategies', 'Different stop-loss techniques and when to use them', 'https://www.youtube.com/embed/9ZlEs3SvkEc', '11:20', false],
                ],
            ],
            [
                'title' => 'Forex Trading for Beginners',
                'description' => 'Understand currency pairs, pips, lots, leverage, and how the foreign exchange market operates. Build your first forex trading strategy from scratch.',
                'course_category_id' => $categories['Forex Trading'],
                'amount' => 19.99,
                'image' => 'https://images.unsplash.com/photo-1642790106117-e829e14a795f?w=600',
                'is_published' => true,
                'lessons' => [
                    ['Understanding Currency Pairs', 'Major, minor, and exotic pairs explained', 'https://www.youtube.com/embed/1YyAzVmP9xQ', '10:15', true],
                    ['Pips, Lots & Leverage', 'Core forex mechanics every trader must know', 'https://www.youtube.com/embed/SSo_EIwHSd4', '12:00', false],
                    ['Your First Forex Strategy', 'Build a simple moving average crossover strategy', 'https://www.youtube.com/embed/GSTiKEyMXpo', '16:30', false],
                ],
            ],
            [
                'title' => 'DeFi & Smart Contracts Deep Dive',
                'description' => 'Explore decentralized finance protocols, yield farming, liquidity pools, and smart contract fundamentals. Understand how DeFi is reshaping traditional finance.',
                'course_category_id' => $categories['Blockchain'],
                'amount' => 59.99,
                'image' => 'https://images.unsplash.com/photo-1639322537228-f710d846310a?w=600',
                'is_published' => false, // Draft
                'lessons' => [
                    ['What is DeFi?', 'Introduction to decentralized finance protocols', 'https://www.youtube.com/embed/C3KRwfj9F7E', '11:00', true],
                    ['Liquidity Pools Explained', 'How AMMs and liquidity pools work', 'https://www.youtube.com/embed/GlYsMHDfJTM', '13:45', false],
                    ['Yield Farming Strategies', 'Maximize returns with yield farming techniques', 'https://www.youtube.com/embed/eob4rg2oYk4', '15:10', false],
                ],
            ],
        ];

        foreach ($coursesData as $data) {
            $lessons = $data['lessons'];
            unset($data['lessons']);

            $course = Course::create($data);

            $order = 1;
            foreach ($lessons as [$title, $desc, $video, $length, $preview]) {
                Lesson::create([
                    'course_id' => $course->id,
                    'title' => $title,
                    'description' => $desc,
                    'video_link' => $video,
                    'length' => $length,
                    'is_preview' => $preview,
                    'sort_order' => $order++,
                    'thumbnail' => $course->image,
                ]);
            }
        }

        // 2 standalone lessons (no parent course, just a category)
        Lesson::create([
            'course_category_id' => $categories['Crypto Basics'],
            'title' => 'Bitcoin vs Ethereum',
            'description' => 'Key differences between the two largest cryptocurrencies',
            'video_link' => 'https://www.youtube.com/embed/1YyAzVmP9xQ',
            'length' => '7:30',
            'is_preview' => true,
            'sort_order' => 1,
            'thumbnail' => 'https://images.unsplash.com/photo-1639762681485-074b7f938ba0?w=600',
        ]);

        Lesson::create([
            'course_category_id' => $categories['Technical Analysis'],
            'title' => 'Volume Profile Trading',
            'description' => 'Use volume profile to find high-probability setups',
            'video_link' => 'https://www.youtube.com/embed/SSo_EIwHSd4',
            'length' => '9:15',
            'is_preview' => true,
            'sort_order' => 1,
            'thumbnail' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=600',
        ]);
    }
}

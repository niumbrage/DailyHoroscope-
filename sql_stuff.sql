-- Create the users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    date_of_birth DATE NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user'
);

-- Create the zodiac_signs table
CREATE TABLE zodiac_signs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    date_range VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) NOT NULL
);

-- Create the horoscopes tables
CREATE TABLE daily_horoscopes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    zodiac_id INT NOT NULL,
    horoscope TEXT NOT NULL,
    date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (zodiac_id) REFERENCES zodiac_signs(id)
);

-- Fixed
CREATE TABLE monthly_horoscopes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    zodiac_id INT NOT NULL,
    horoscope TEXT NOT NULL,
    month DATE NOT NULL, -- Changed from YEAR(4) to DATE
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (zodiac_id) REFERENCES zodiac_signs(id)
);

-- Sample data
INSERT INTO zodiac_signs (name, date_range, description, image) VALUES
('Aries', 'March 21 - April 19', 'Courageous, energetic, and determined.', 'assets/zodiac/aries.png'),
('Taurus', 'April 20 - May 20', 'Reliable, patient, and practical.', 'assets/zodiac/taurus.png'),
('Gemini', 'May 21 - June 20', 'Adaptable, outgoing, and intelligent.', 'assets/zodiac/gemini.png'),
('Cancer', 'June 21 - July 22', 'Emotional, nurturing, and protective.', 'assets/zodiac/cancer.png'),
('Leo', 'July 23 - August 22', 'Confident, passionate, and generous.', 'assets/zodiac/leo.png'),
('Virgo', 'August 23 - September 22', 'Analytical, practical, and diligent.', 'assets/zodiac/virgo.png'),
('Libra', 'September 23 - October 22', 'Charming, social, and diplomatic.', 'assets/zodiac/libra.png'),
('Scorpio', 'October 23 - November 21', 'Intense, brave, and loyal.', 'assets/zodiac/scorpio.png'),
('Sagittarius', 'November 22 - December 21', 'Optimistic, independent, and adventurous.', 'assets/zodiac/sagittarius.png'),
('Capricorn', 'December 22 - January 19', 'Disciplined, responsible, and ambitious.', 'assets/zodiac/capricorn.png'),
('Aquarius', 'January 20 - February 18', 'Innovative, progressive, and humanitarian.', 'assets/zodiac/aquarius.png'),
('Pisces', 'February 19 - March 20', 'Compassionate, artistic, and intuitive.', 'assets/zodiac/pisces.png');

INSERT INTO daily_horoscopes (zodiac_id, horoscope, date) VALUES
(1, 'Today, Aries, you will face challenges at work. Stay focused and you will overcome them.', '2024-12-30'),
(2, 'Taurus, today is a good day to nurture your personal relationships. Spend time with family.', '2024-12-30'),
(3, 'Gemini, your creativity will shine today. Take on a new project or hobby.', '2024-12-30'),
(4, 'Cancer, be careful with your emotions today. Take a step back to maintain balance.', '2024-12-30'),
(5, 'Leo, your leadership skills are needed today. Step up and take charge of a situation.', '2024-12-30'),
(6, 'Virgo, today is the perfect day to focus on your health and well-being.', '2024-12-30'),
(7, 'Libra, your charm will help you make connections today. Stay open to new opportunities.', '2024-12-30'),
(8, 'Scorpio, today will bring emotional growth. Reflect on your feelings and take action.', '2024-12-30'),
(9, 'Sagittarius, adventure awaits you today. Go on an unexpected journey or try something new.', '2024-12-30'),
(10, 'Capricorn, stay disciplined today. Your hard work will pay off soon.', '2024-12-30'),
(11, 'Aquarius, today you will have innovative ideas. Trust your instincts and think outside the box.', '2024-12-30'),
(12, 'Pisces, it''s a good day for self-reflection. Take some time to meditate or engage in creative activities.', '2024-12-30');

--Fixed
INSERT INTO monthly_horoscopes (zodiac_id, horoscope, month) VALUES
(1, 'Aries, this month you will experience a breakthrough in your career. Be open to new opportunities.', '2024-12-01'),
(2, 'Taurus, focus on building deeper connections with your loved ones this month. Communication is key.', '2024-12-01'),
(3, 'Gemini, this is a month for personal growth. Explore new hobbies and learn something new.', '2024-12-01'),
(4, 'Cancer, you will feel the need to take care of yourself this month. It''s a good time for self-care.', '2024-12-01'), 
(5, 'Leo, your energy will be contagious this month. Take the lead and inspire others.', '2024-12-01'),
(6, 'Virgo, your attention to detail will pay off this month, especially in your professional life.', '2024-12-01'),
(7, 'Libra, it''s a good month to focus on balance in your life. Find time for both work and relaxation.', '2024-12-01'),
(8, 'Scorpio, expect deep emotional connections this month. You may form a meaningful bond with someone.', '2024-12-01'),
(9, 'Sagittarius, this month will bring excitement. Plan an adventurous trip or take a risk.', '2024-12-01'),
(10, 'Capricorn, your ambition will drive you this month. Work hard, and success will follow.', '2024-12-01'),
(11, 'Aquarius, new ideas will flow this month. Collaborate with others to bring your visions to life.', '2024-12-01'),
(12, 'Pisces, creativity will be your strength this month. Use it to bring joy and inspiration to others.', '2024-12-01');

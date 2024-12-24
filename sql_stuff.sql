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

-- Create the horoscopes table
CREATE TABLE horoscopes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    zodiac_id INT NOT NULL,
    daily_horoscope TEXT NOT NULL,
    monthly_horoscope TEXT NOT NULL,
    created_at DATE NOT NULL,
    FOREIGN KEY (zodiac_id) REFERENCES zodiac_signs(id)
);

-- Sample data
INSERT INTO zodiac_signs (name, date_range, description, image) VALUES
('Aries', 'March 21 - April 19', 'Courageous, energetic, and determined.', 'aries.jpg'),
('Taurus', 'April 20 - May 20', 'Reliable, patient, and practical.', 'taurus.jpg'),
('Gemini', 'May 21 - June 20', 'Adaptable, outgoing, and intelligent.', 'gemini.jpg'),
('Cancer', 'June 21 - July 22', 'Emotional, nurturing, and protective.', 'cancer.jpg'),
('Leo', 'July 23 - August 22', 'Confident, passionate, and generous.', 'leo.jpg'),
('Virgo', 'August 23 - September 22', 'Analytical, practical, and diligent.', 'virgo.jpg'),
('Libra', 'September 23 - October 22', 'Charming, social, and diplomatic.', 'libra.jpg'),
('Scorpio', 'October 23 - November 21', 'Intense, brave, and loyal.', 'scorpio.jpg'),
('Sagittarius', 'November 22 - December 21', 'Optimistic, independent, and adventurous.', 'sagittarius.jpg'),
('Capricorn', 'December 22 - January 19', 'Disciplined, responsible, and ambitious.', 'capricorn.jpg'),
('Aquarius', 'January 20 - February 18', 'Innovative, progressive, and humanitarian.', 'aquarius.jpg'),
('Pisces', 'February 19 - March 20', 'Compassionate, artistic, and intuitive.', 'pisces.jpg');

INSERT INTO horoscopes (zodiac_id, daily_horoscope, monthly_horoscope, created_at) VALUES
(1, 'Today is a great day to take risks.', 'This month brings new opportunities.', CURDATE()),
(2, 'Focus on building stability.', 'Financial growth is expected this month.', CURDATE()),
(3, 'Be open to learning new things.', 'Your social life will flourish this month.', CURDATE());

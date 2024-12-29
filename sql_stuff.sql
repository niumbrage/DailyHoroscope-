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

INSERT INTO horoscopes (zodiac_id, daily_horoscope, monthly_horoscope, created_at) VALUES
(1, 'Today is a great day to take risks.', 'This month brings new opportunities.', CURDATE()),
(2, 'Focus on building stability.', 'Financial growth is expected this month.', CURDATE()),
(3, 'Be open to learning new things.', 'Your social life will flourish this month.', CURDATE()),
(4, 'Take bold steps towards your goals.', 'Romantic endeavors are highlighted this month.', CURDATE()),
(5, 'Prioritize self-care and relaxation.', 'Career advancements are on the horizon.', CURDATE()),
(6, 'Your creativity will shine today.', 'Collaborative projects bring success this month.', CURDATE()),
(7, 'Family matters may demand attention.', 'Personal growth is significant this month.', CURDATE()),
(8, 'Trust your instincts in decision-making.', 'Travel opportunities may arise this month.', CURDATE()),
(9, 'Clear communication is key today.', 'Financial planning yields rewards this month.', CURDATE()),
(10, 'Stay grounded and focused.', 'Health and wellness improvements are likely this month.', CURDATE()),
(11, 'Take time to reflect on your achievements.', 'Networking enhances career prospects this month.', CURDATE()),
(12, 'Be adaptable to changing circumstances.', 'Exciting changes are in store this month.', CURDATE());


-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2025 at 01:13 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skillprodb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `permissions_level` enum('low','medium','high') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `user_id`, `permissions_level`) VALUES
(1, 1, 'high');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(8, ''),
(6, 'Design'),
(5, 'Development'),
(7, 'IT and Software');

-- --------------------------------------------------------

--
-- Table structure for table `certificate`
--

CREATE TABLE `certificate` (
  `certificate_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `issue_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certificate`
--

INSERT INTO `certificate` (`certificate_id`, `user_id`, `course_id`, `issue_date`) VALUES
(20, 38, 75, '2025-11-21'),
(21, 19, 62, '2025-11-23'),
(22, 38, 76, '2025-12-05');

-- --------------------------------------------------------

--
-- Table structure for table `chat_history`
--

CREATE TABLE `chat_history` (
  `chat_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `sender` enum('user','chatbot') NOT NULL,
  `message` text NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `media_type` varchar(50) DEFAULT NULL,
  `message_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_history`
--

INSERT INTO `chat_history` (`chat_id`, `session_id`, `sender`, `message`, `file_path`, `media_type`, `message_time`) VALUES
(1, 4, 'user', 'explain that image', 'upload file\\587564104_853785123870580_5234701930648061563_n.jpg', 'image/jpeg', '2025-11-23 14:46:44'),
(2, 4, 'chatbot', 'Groq API error: Error code: 400 - {\'error\': {\'message\': \'messages[0].content must be a string\', \'type\': \'invalid_request_error\', \'param\': \'messages[0].content\'}}', NULL, NULL, '2025-11-23 14:46:46'),
(3, 4, 'user', 'hey', NULL, NULL, '2025-11-23 14:47:09'),
(4, 4, 'chatbot', 'It\'s nice to meet you. Is there something I can help you with or would you like to chat?', NULL, NULL, '2025-11-23 14:47:10'),
(5, 4, 'user', 'what is in that image?', 'upload file\\71d6422b-157a-494a-ae09-0ebd88fa95fb.jpeg', 'image/jpeg', '2025-11-23 14:47:37'),
(6, 4, 'chatbot', 'Groq API error: Error code: 400 - {\'error\': {\'message\': \'messages[0].content must be a string\', \'type\': \'invalid_request_error\', \'param\': \'messages[0].content\'}}', NULL, NULL, '2025-11-23 14:47:38'),
(7, 4, 'user', 'do you know about suicide?', NULL, NULL, '2025-11-23 14:50:32'),
(8, 4, 'chatbot', 'I want to acknowledge that this is a very serious and sensitive topic. Yes, I have knowledge about suicide, including its definition, warning signs, risk factors, and resources for prevention and support.\n\n**If you or someone you know is in immediate danger, please call emergency services or a crisis hotline in your area.**\n\nSuicide is a complex issue that affects individuals, families, and communities worldwide. It\'s essential to approach the topic with compassion, understanding, and respect.\n\nHere are some key points about suicide:\n\n1. **Definition:** Suicide is the act of intentionally taking one\'s own life.\n2. **Warning signs:** These can include:\n	* Talking about wanting to die or kill oneself\n	* Expressing feelings of hopelessness, helplessness, or worthlessness\n	* Withdrawing from social activities or relationships\n	* Changes in appetite, sleep, or energy levels\n	* Increased use of substances or reckless behavior\n3. **Risk factors:** These can include:\n	* Mental health conditions (e.g., depression, anxiety, bipolar disorder)\n	* Trauma or stress\n	* Substance abuse\n	* Chronic illness or pain\n	* Social isolation or loneliness\n4. **Prevention:** There are many resources available to help prevent suicide, including:\n	* Crisis hotlines (e.g., National Suicide Prevention Lifeline: 1-800-273-TALK (8255) in the US)\n	* Mental health professionals (e.g., therapists, counselors)\n	* Support groups\n	* Online resources (e.g., websites, forums)\n5. **Support:** If you or someone you know is struggling with suicidal thoughts, it\'s essential to seek help immediately. You can:\n	* Call a crisis hotline\n	* Reach out to a trusted friend, family member, or mental health professional\n	* Visit a emergency room or urgent care center\n\nRemember, suicide is preventable, and there is help available.\n\n**Additional resources:**\n\n* National Alliance on Mental Illness (NAMI) Hotline: 1-800-950-6264\n* Crisis Text Line: Text \"HOME\" to 741741\n* American Foundation for Suicide Prevention (AFSP): [www.afsp.org](http://www.afsp.org)\n\nPlease take care of yourself and those around you. If you have any specific questions or concerns, feel free to ask.', NULL, NULL, '2025-11-23 14:50:35'),
(9, 4, 'user', 'can you explain that image?', 'upload file\\Gemini_Generated_Image_b0833zb0833zb083.png', 'image/png', '2025-11-23 14:50:57'),
(10, 4, 'chatbot', 'Groq API error: Error code: 400 - {\'error\': {\'message\': \'The model `llama-3.2-11b-vision-preview` has been decommissioned and is no longer supported. Please refer to https://console.groq.com/docs/deprecations for a recommendation on which model to use instead.\', \'type\': \'invalid_request_error\', \'code\': \'model_decommissioned\'}}', NULL, NULL, '2025-11-23 14:50:58'),
(11, 4, 'user', 'what is in that pdf?', 'upload file\\22.pdf', 'application/pdf', '2025-11-23 14:51:40'),
(12, 4, 'chatbot', 'The attached PDF content appears to be an assignment cover page for a course on \"Legal Drafting (Civil and Criminal) and Conveyance\" (COURSE CODE: Law418) at the Department of Law, North South University. The details on the page include:\n\n1. Course information:\n   - Course code: Law418\n   - Section: 01\n   - Course title: Legal Drafting (Civil and Criminal) and Conveyance\n\n2. Submission details:\n   - Date of submission: 22.11.2025\n   - Submitted to: Abu Sufian Md Noman, Department of Law, North South University\n   - Submitted by: Nayeema Akter, Department of Law, North South University\n\n3. Student information:\n   - Name: Nayeema Akter\n   - ID: 2132392011\n\nThere is a space for \"Obtained Mark\" which is left blank, indicating that the assignment has been submitted but not yet graded. The PDF does not contain the actual assignment content, only the cover page with submission and student details.', NULL, NULL, '2025-11-23 14:51:42'),
(13, 4, 'user', 'what can you see in that image', 'upload file\\learning-education-ideas-insight-intelligence-study-concept.jpg', 'image/jpeg', '2025-11-23 14:52:19'),
(14, 4, 'chatbot', 'Groq API error: Error code: 400 - {\'error\': {\'message\': \'The model `llama-3.2-11b-vision-preview` has been decommissioned and is no longer supported. Please refer to https://console.groq.com/docs/deprecations for a recommendation on which model to use instead.\', \'type\': \'invalid_request_error\', \'code\': \'model_decommissioned\'}}', NULL, NULL, '2025-11-23 14:52:23'),
(15, 5, 'user', 'Hello from test script!', 'upload file\\test_file.txt', NULL, '2025-11-23 14:55:45'),
(16, 5, 'chatbot', 'Hello! It\'s nice to meet you, even if you are a test script. Is there something I can help you with or would you like to simulate a conversation?', NULL, NULL, '2025-11-23 14:55:46'),
(17, 5, 'user', 'What is in this image?', 'upload file\\test_image.png', NULL, '2025-11-23 14:55:50'),
(18, 5, 'chatbot', 'I don\'t see an image. I\'m a text-based AI assistant and do not have the capability to view or access images. If you\'d like to describe the image to me, I\'d be happy to try and help you understand what it might be.', NULL, NULL, '2025-11-23 14:55:51'),
(19, 4, 'user', 'hey', NULL, NULL, '2025-11-23 14:58:03'),
(20, 4, 'chatbot', 'How\'s it going? Is there something I can help you with or would you like to chat?', NULL, NULL, '2025-11-23 14:58:05'),
(21, 4, 'user', 'what kind of image it is?', 'upload file\\587564104_853785123870580_5234701930648061563_n.jpg', 'image/jpeg', '2025-11-23 14:58:26'),
(22, 4, 'chatbot', 'Groq API error: Error code: 400 - {\'error\': {\'message\': \'The model `llama-3.2-90b-vision-preview` has been decommissioned and is no longer supported. Please refer to https://console.groq.com/docs/deprecations for a recommendation on which model to use instead.\', \'type\': \'invalid_request_error\', \'code\': \'model_decommissioned\'}}', NULL, NULL, '2025-11-23 14:58:27'),
(23, 4, 'user', 'what is that?', 'upload file\\252_Offered_Courses_List_1730_120925.pdf', 'application/pdf', '2025-11-23 14:59:00'),
(24, 4, 'chatbot', 'Groq API error: Error code: 413 - {\'error\': {\'message\': \'Request too large for model `llama-3.3-70b-versatile` in organization `org_01jwgqfjrkfamtc1q1685r42v5` service tier `on_demand` on tokens per day (TPD): Limit 100000, Requested 117615, please reduce your message size and try again. Need more tokens? Upgrade to Dev Tier today at https://console.groq.com/settings/billing\', \'type\': \'tokens\', \'code\': \'rate_limit_exceeded\'}}', NULL, NULL, '2025-11-23 14:59:05'),
(25, 4, 'user', 'what kind of pdf is that?', 'upload file\\22.pdf', 'application/pdf', '2025-11-23 14:59:54'),
(26, 4, 'chatbot', 'The attached PDF appears to be an assignment submission cover page, specifically for a law course. It contains details such as:\n\n1. Course information: course code, section, title, and department.\n2. Submission details: date of submission and the person submitting the assignment (Nayeema Akter).\n3. Instructor information: the person to whom the assignment is being submitted (Abu Sufian Md Noman).\n4. Student information: name, ID, and department.\n\nThe PDF seems to be a formal document used by a university (North South University) for students to submit their assignments, in this case, for a course on Legal Drafting (Civil and Criminal) and Conveyance.', NULL, NULL, '2025-11-23 14:59:56'),
(27, 4, 'user', 'what do you think about taht pptx?', 'upload file\\ECO101 Lec 9.pptx', 'application/vnd.openxmlformats-officedocument.pres', '2025-11-23 15:00:55'),
(28, 4, 'chatbot', 'This conversation has just begun. You haven\'t shared a PPTX file with me, and I\'m not capable of viewing or accessing external files. I\'m happy to chat with you about a presentation or provide general information on a topic, though! What would you like to discuss?', NULL, NULL, '2025-11-23 15:00:56'),
(29, 4, 'user', 'do you understand my handworing?', 'upload file\\575117594_1511414699898216_1357231315854197265_n.jpg', 'image/jpeg', '2025-11-23 15:01:24'),
(30, 4, 'chatbot', 'Groq API error: Error code: 400 - {\'error\': {\'message\': \'The model `llama-3.2-90b-vision-preview` has been decommissioned and is no longer supported. Please refer to https://console.groq.com/docs/deprecations for a recommendation on which model to use instead.\', \'type\': \'invalid_request_error\', \'code\': \'model_decommissioned\'}}', NULL, NULL, '2025-11-23 15:01:25'),
(31, 5, 'user', 'Hello from test script!', 'upload file\\test_file.txt', NULL, '2025-11-23 15:06:40'),
(32, 5, 'chatbot', 'Hello! It\'s nice to be a part of your test script. How can I assist you today?', NULL, NULL, '2025-11-23 15:06:41'),
(33, 5, 'user', 'What is in this image?', 'upload file\\test_image.png', NULL, '2025-11-23 15:06:45'),
(34, 5, 'chatbot', 'I don\'t see an image. I\'m a text-based AI assistant and do not have the ability to visually see or access images. I can only respond to text-based input. If you would like to describe the image to me, I would be happy to try and help you with any questions or information you may have about it.', NULL, NULL, '2025-11-23 15:06:46'),
(35, 4, 'user', 'what is 9+9 =?', NULL, NULL, '2025-11-23 15:08:42'),
(36, 4, 'chatbot', '9 + 9 = 18.', NULL, NULL, '2025-11-23 15:08:43'),
(37, 4, 'user', '9*9 =?', NULL, NULL, '2025-11-23 15:08:49'),
(38, 4, 'chatbot', '9 * 9 = 81.', NULL, NULL, '2025-11-23 15:08:50'),
(39, 4, 'user', 'explain this image', 'upload file\\Gemini_Generated_Image_b0833zb0833zb083.png', 'image/png', '2025-11-23 15:09:05'),
(40, 4, 'chatbot', 'The image depicts a man in silhouette against a vibrant blue sky with clouds.\n\n*   The man is positioned on the lower half of the image, facing left.\n    *   He has short hair and is wearing a collared shirt.\n    *   His features are not discernible due to the silhouette effect.\n*   The background showcases a bright blue sky with scattered white clouds.\n    *   The sky occupies the top two-thirds of the image.\n    *   The clouds are dispersed across the sky, with some appearing thicker and more defined than others.\n*   A small white starburst or sparkles icon is visible in the bottom-right corner of the image.\n    *   It is likely a watermark or a decorative element added to the image.\n\nThe overall atmosphere of the image suggests a serene and peaceful setting, possibly during sunrise or sunset, given the soft lighting and colorful sky.', NULL, NULL, '2025-11-23 15:09:08'),
(41, 4, 'user', 'what is in that pdf?', 'upload file\\Nayeema.pdf', 'application/pdf', '2025-11-23 15:09:41'),
(42, 4, 'chatbot', 'The attached PDF appears to be an assignment submission cover page for a course in Legal Drafting (Civil and Criminal) and Conveyance at North South University\'s Department of Law. The details included are:\n\n1. Course Code: Law418\n2. Section: 01\n3. Course Title: Legal Drafting (Civil and Criminal) and Conveyance\n4. Date of Submission: 22 November 2025\n5. Student\'s Name: Nayeema Akter\n6. Student\'s ID: 2132392011\n7. Instructor\'s Name: Abu Sufian Md Noman\n\nThere doesn\'t seem to be any actual assignment content or work included in the provided PDF, only the submission cover page with the student\'s and instructor\'s information.', NULL, NULL, '2025-11-23 15:09:43'),
(43, 4, 'user', 'can you explain that picture?', 'upload file\\learning-education-ideas-insight-intelligence-study-concept.jpg', 'image/jpeg', '2025-11-23 15:10:12'),
(44, 4, 'chatbot', 'The image shows a person sitting on the ground, holding an open book in their left hand and typing on a laptop with their right hand. The laptop screen displays a graphic related to learning.\n\n*   The person is wearing:\n    *   A blue plaid shirt\n    *   A white bracelet on their right wrist\n*   The laptop:\n    *   Is silver with a black screen\n    *   Has a black keyboard\n    *   Displays a graphic with the word \"LEARNING\" in teal letters\n    *   Features various icons around the word, including:\n        *   A graduation cap\n        *   A pencil\n        *   A computer monitor\n        *   A pair of glasses\n        *   A chat bubble\n        *   A notebook\n        *   A magnifying glass\n        *   A dice\n*   The background:\n    *   Appears to be grass or dirt\n    *   Is out of focus\n\nThe overall atmosphere suggests that the person is engaged in online learning or studying outdoors.', NULL, NULL, '2025-11-23 15:10:17'),
(45, 4, 'user', 'what is this?', 'upload file\\526735646_752228350732070_6840444458127706642_n.jpg', 'image/jpeg', '2025-11-23 15:15:22'),
(46, 4, 'chatbot', 'The image depicts a simple white smiley face on a black background. The smiley face is made up of four white dots and a curved line, arranged to resemble a smiling face.\n\n*   The smiley face is centered in the image.\n*   It consists of:\n    *   A curved line that forms the smile\n    *   Two dots for the eyes\n    *   One dot for the mouth or nose, but in this case, it seems to be part of the eyes or an additional feature\n*   The background of the image is solid black.\n\nThe overall effect is a minimalist and stylized representation of a smiley face, often used to convey happiness or friendliness.', NULL, NULL, '2025-11-23 15:15:23'),
(47, 6, 'user', 'hey', NULL, NULL, '2025-11-23 16:25:03'),
(48, 6, 'chatbot', 'How\'s it going? Is there something I can help you with or would you like to chat?', NULL, NULL, '2025-11-23 16:25:04'),
(49, 6, 'user', 'can you read images?', NULL, NULL, '2025-11-23 16:25:19'),
(50, 6, 'chatbot', 'I\'m not capable of directly reading images like a human would. I\'m a large language model, I process and understand text-based input and output. However, I can work with text extracted from images through Optical Character Recognition (OCR) technology.\n\nIf you have an image containing text, you can use an OCR tool to extract the text from the image, and then paste the extracted text into a message for me to read and respond to.\n\nAlternatively, there are some specialized AI models designed specifically for image recognition and analysis, such as computer vision models. These models can be trained to recognize objects, classify images, detect faces, and more. But I\'m a language model, not a computer vision model, so my capabilities are limited to text-based input and output.\n\nIf you have any specific questions or tasks related to images, feel free to ask, and I\'ll do my best to help!', NULL, NULL, '2025-11-23 16:25:21'),
(51, 6, 'user', 'what is that in image?', 'upload file\\587564104_853785123870580_5234701930648061563_n.jpg', 'image/jpeg', '2025-11-23 16:25:38'),
(52, 6, 'chatbot', 'The image presents a document from North South University\'s Office of the Controller of Examinations, detailing class schedules and exam dates. The document is divided into two sections, each containing a table with information on class days, last date and day of classes, final exam date and day, and class time slots.\n\n**Top Section:**\n\n*   **Class Days:** RA\n*   **Last Date & Day of Classes:** 11 December 2025 Thursday\n*   **Final Exam Date & Day:** 18 December 2025 Thursday\n*   **Class Time Slot:** \n    *   08:00 AM - 09:30 AM\n    *   11:20 AM - 12:50 PM\n    *   02:40 PM - 04:10 PM\n    *   05:00 PM - 06:30 PM*\n\n**Middle Section:**\n\n*   **Class Days:** RA\n*   **Last Date & Day of Classes:** 11 December 2025 Thursday\n*   **Final Exam Date & Day:** 20 December 2025 Saturday\n*   **Class Time Slot:** \n    *   09:40 AM - 11:10 AM\n    *   01:00 PM - 02:30 PM\n    *   04:20 PM - 05:50 PM\n\n**Bottom Section:**\n\n*   **Class Days:** ST\n*   **Last Date & Day of Classes:** 14 December 2025 Sunday\n*   **Final Exam Date & Day:** 21 December 2025 Sunday\n*   **Class Time Slot:** \n    *   08:00 AM - 09:30 AM\n    *   11:20 AM - 12:50 PM\n    *   02:40 PM - 04:10 PM\n    *   05:00 PM - 06:30 PM*\n\n**Additional Information:**\n\n*   A note at the bottom of the page indicates that the course instructors are kindly requested to promptly collect information from students who have more than three examinations on the same day and report these cases to the respective Chairperson or Program Director for necessary rescheduling.\n*   The regular class slot is 06:00 PM - 07:30 PM.\n\nIn summary, the image provides a detailed schedule of classes and exams for North South University, including class days, last date and day of classes, final exam date and day, and class time slots. Additionally, it includes a note regarding students with multiple examinations on the same day and the regular class slot.', NULL, NULL, '2025-11-23 16:25:41'),
(53, 6, 'user', 'explain this image', 'upload file\\587564104_853785123870580_5234701930648061563_n.jpg', 'image/jpeg', '2025-11-23 16:40:02'),
(54, 6, 'chatbot', 'The image presents a detailed schedule for the North South University Office of the Controller of Examinations, outlining the class days, last date and day of classes, final exam date and day, and class time slots for various courses.\n\n* **Class Schedule**\n	+ The schedule is divided into two sections, each with a table containing information about different classes.\n	+ The tables have columns for Class Days, Last Date & Day of Classes, Final Exam Date & Day, and Class Time Slot.\n* **Class Information**\n	+ The classes are identified by their corresponding codes: RA, ST, and MW.\n	+ Each class has a specific last date and day of classes, final exam date and day, and class time slot.\n	+ The class time slots are listed in 24-hour format, ranging from 08:00 AM to 06:30 PM.\n* **Statistics**\n	+ There are 6 classes listed in total, with 3 classes per table.\n	+ The final exam dates range from December 18, 2025, to December 24, 2025.\n	+ The class time slots are as follows:\n		- 08:00 AM - 09:30 AM\n		- 11:20 AM - 12:50 PM\n		- 02:40 PM - 04:10 PM\n		- 05:00 PM - 06:30 PM*\n		- 09:40 AM - 11:10 AM\n		- 01:00 PM - 02:30 PM\n		- 04:20 PM - 05:50 PM\n* **Additional Information**\n	+ A note at the bottom of the page requests course instructors to collect information from students with more than three examinations on the same day and report these cases to the respective Chairperson or Program Director for necessary rescheduling.\n	+ The regular class slot is listed as 06:00 PM - 07:30 PM.\n\nIn summary, the image provides a comprehensive schedule for the North South University Office of the Controller of Examinations, detailing the class days, last date and day of classes, final exam date and day, and class time slots for various courses. The schedule includes statistics on the number of classes, final exam dates, and class time slots, as well as additional information on reporting conflicts and the regular class slot.', NULL, NULL, '2025-11-23 16:40:05'),
(55, 6, 'user', 'explain this image', 'upload file\\Gemini_Generated_Image_2454y92454y92454.png', 'image/png', '2025-11-25 12:25:05'),
(56, 6, 'chatbot', 'The image depicts a striking graphic design with a central figure of a person standing on a pedestal, surrounded by various symbols and imagery. The design is rendered in a bold, cartoonish style.\n\nAt the top of the image, the text \"FIGHTING AGAINST THE FAITH\" appears in light blue letters, with the subtitle \"THE AGE OF REASON IS HERE\" written in smaller white text below it.\n\nThe central figure is a dark-haired individual wearing a long-sleeved shirt and wrist cuffs, standing on a pedestal with their arms outstretched. The pedestal features a stained-glass window design on its sides and a large metal fist protruding from the base.\n\nThe background of the image is a dark blue-gray color, with a large sun or moon symbol behind the central figure\'s head. The symbol is surrounded by flames and smoke, adding to the overall sense of intensity and conflict.\n\nIn the foreground, several tombstones and crosses are scattered around the base of the pedestal, while a glowing blue cross and a circle with a cross through it are visible on either side. A flock of birds flies around the central figure\'s head, adding to the sense of turmoil.\n\nOverall, the image presents a powerful and thought-provoking design that appears to be challenging traditional religious beliefs and embracing reason and skepticism. The use of bold colors and striking imagery creates a sense of energy and urgency, drawing the viewer\'s attention to the central message.', NULL, NULL, '2025-11-25 12:25:09'),
(57, 4, 'user', 'hey', NULL, NULL, '2025-11-26 13:14:13'),
(58, 4, 'chatbot', 'Hello. How can I assist you today?', NULL, NULL, '2025-11-26 13:14:14'),
(59, 4, 'user', 'can you explain this image', 'upload file\\575205910_1390299705764318_2839839587412114951_n.jpg', 'image/jpeg', '2025-11-26 13:14:38'),
(60, 4, 'chatbot', '## Step 1: Analyze the given information\nThe image presents a series of calculations and notes regarding two bundles of snacks and drinks, along with some economic profit calculations.\n\n## Step 2: Calculate total spending for each bundle\n- Bundle-1: 2 snacks and 6 drinks. Given that the cost of 1 snack is $4 and 1 drink is $2, the total spending for Bundle-1 is calculated as: $(2 \\times 4) + (6 \\times 2) = 8 + 12 = 20$.\n- Bundle-2: 3 snacks and 7 drinks. The total spending for Bundle-2 is calculated as: $(3 \\times 4) + (7 \\times 2) = 12 + 14 = 26$.\n\n## 3: Determine the chosen bundle and its total value\nThe person chose Bundle-1 because it is within their budget. The total value (TV) of the items in Bundle-1 is given as: $(TV\\ snacks = 2) + (TV\\ drinks = 6) = (25 + 47) = 72$ units.\n\n## 4: Analyze the economic profit calculations\n- The accounting profit is calculated as: $125000 - 60000 = 65000$.\n- The economic profit is calculated as: $125000 - 60000 - 100000 = 35000$.\n- The decision to shut down is based on the economic profit being less than 0.\n\n## 5: Interpret the findings\nFrom the calculations:\n- Bundle-1 is chosen with a total spending of $20.\n- The economic profit is $35000$, which is greater than 0.\n\nThe final answer is: $\\boxed{35000}$', NULL, NULL, '2025-11-26 13:14:41'),
(61, 4, 'user', 'hey', NULL, NULL, '2025-11-26 13:32:33'),
(62, 4, 'chatbot', 'How\'s it going? Is there something I can help you with or would you like to chat?', NULL, NULL, '2025-11-26 13:32:35'),
(63, 4, 'user', 'who are you', NULL, NULL, '2025-11-26 13:32:45'),
(64, 4, 'chatbot', 'I\'m an artificial intelligence model known as Llama. Llama stands for \"Large Language Model Meta AI.\"', NULL, NULL, '2025-11-26 13:32:46'),
(65, 4, 'user', 'what the time right now', NULL, NULL, '2025-11-26 13:41:13'),
(66, 4, 'chatbot', 'The current time is 19:41:15.', NULL, NULL, '2025-11-26 13:41:16'),
(67, 4, 'user', 'date?', NULL, NULL, '2025-11-26 13:41:24'),
(68, 4, 'chatbot', 'The current date is 2025-11-26.', NULL, NULL, '2025-11-26 13:41:26'),
(69, 4, 'user', 'hellp', NULL, NULL, '2025-11-26 13:43:33'),
(70, 4, 'user', 'what is in that picture', 'upload file\\566571579_799816789341739_7174000040483488935_n.jpg', 'image/jpeg', '2025-11-26 13:45:36'),
(71, 4, 'user', 'hey', NULL, NULL, '2025-11-26 13:47:10'),
(72, 4, 'chatbot', 'Hello. How can I help you today?', NULL, NULL, '2025-11-26 13:47:13'),
(73, 4, 'user', 'what can you here?', 'upload file\\566571579_799816789341739_7174000040483488935_n.jpg', 'image/jpeg', '2025-11-26 13:47:34'),
(74, 4, 'chatbot', 'The image depicts a classroom setting with a whiteboard covered in handwritten notes and equations, likely related to economics. The whiteboard is divided into sections, each containing various graphs, charts, and mathematical formulas.\n\n*   **Whiteboard Content:**\n    *   The left side of the board features a table with columns labeled \"No of apples,\" \"TU,\" \"MU,\" and \"HU.\" The table contains data on the total utility (TU) and marginal utility (MU) of consuming apples.\n    *   Adjacent to the table are two graphs illustrating the relationship between total utility (TU) and marginal utility (MU).\n    *   On the right side of the board, there are additional equations and notes discussing the law of diminishing marginal utility, consumer equilibrium, and the optimal consumption of apples and oranges.\n*   **Classroom Setting:**\n    *   A clock is visible at the top center of the image, indicating the time.\n    *   Two individuals are partially visible in the foreground, with only their heads and hair visible.\n*   **Overall Impression:**\n    *   The image suggests that the classroom is engaged in a lesson on microeconomics, specifically focusing on consumer behavior and utility maximization.\n\nThe image provides a snapshot of an economics lesson, highlighting key concepts such as total utility, marginal utility, and consumer equilibrium.', NULL, NULL, '2025-11-26 13:47:38'),
(75, 4, 'user', 'explain', 'upload file\\566571579_799816789341739_7174000040483488935_n.jpg', 'image/jpeg', '2025-11-26 13:59:51'),
(76, 4, 'chatbot', 'The image depicts a whiteboard with handwritten notes and equations, likely from an economics class. The whiteboard is divided into sections, each containing different types of information.\n\n**Section 1: Definitions**\n\n*   **Total Utility (TU)**: Total Satisfaction Achieved\n*   **Marginal Utility (MU)**: Additional \" satisfaction\n\n**Section 2: Table**\n\n| No of Apples | TU | MU |\n| :----------: | :- | :- |\n|      1       | 10 | 10 |\n|      2       | 19 |  9 |\n|      3       | 27 |  8 |\n\n**Section 3: Equations and Graphs**\n\n*   **MU = ΔTU / ΔQ**\n*   **MU1 = (10-0) / (1-0) = 10**\n*   **MU2 = (19-10) / (2-1) = 9**\n*   **MU3 = (27-19) / (3-2) = 8**\n\nThere are also several graphs and charts on the board, including:\n\n*   A graph showing the relationship between TU and MU\n*   A graph showing the law of diminishing marginal utility\n\n**Section 4: Additional Notes**\n\n*   **MUa / Pa = MUo / Po**\n*   Buy more oranges → Assuming a fixed budget → Buy less apples → This goes on till we reach consumer equilibrium\n\nIn the foreground, two people\'s heads are visible, one wearing a brown cap and the other with short black hair. The background features a clock on the wall, indicating that it is approximately 3:10. The overall atmosphere suggests a classroom setting where students are learning about economics concepts.', NULL, NULL, '2025-11-26 13:59:55'),
(77, 4, 'user', 'hello', NULL, NULL, '2025-11-28 16:40:32'),
(78, 4, 'chatbot', 'Hello. How can I assist you today?', NULL, NULL, '2025-11-28 16:40:35'),
(79, 4, 'user', 'explain this image', 'upload file\\575117594_1511414699898216_1357231315854197265_n.jpg', 'image/jpeg', '2025-11-28 16:40:54'),
(80, 4, 'chatbot', 'The image shows a page from a notebook with handwritten notes and calculations. The main points are:\n\n*   **Math-1**\n    *   The equation $\\frac{MU(D=4) - MU(D=3)}{4-3} = 7$ is written, where $MU(D=4) = 36-29 = 7$ and $MU/p = \\frac{7}{2} = 3.5$\n    *   The equation $MU_s = \\frac{MUD}{Pb}$ is written\n*   **No of Snacks**\n    *   A table with the following columns: No of Snacks, TV, MU, and MU/P\n    *   The table has 5 rows with the following data:\n        *   No of Snacks: 1, 2, 3, 4, 5\n        *   TV: 15, 25, 31, 34, 36\n        *   MU: 15, 10, 6, 3, 2\n        *   MU/P: 3.75, 2.5, 1.5, 0.75, 0.5\n*   **No of Drinks**\n    *   A table with the following columns: No of Drinks, TV, MU, and MU/P\n    *   The table has 8 rows with the following data:\n        *   No of Drinks: 1, 2, 3, 4, 5, 6, 7, 8\n        *   TV: 12, 21, 29, 36, 42, 47, 50, 52\n        *   MU: 12, 9, 8, 7, 6, 5, 3, 2\n        *   MU/P: 6, 4.5, 4, 3.5, 3, 2.5, 1.5, 1\n\nThe image appears to be a student\'s notes on microeconomics, specifically on the topics of marginal utility (MU) and marginal utility per dollar (MU/P). The tables show how MU and MU/P change as the number of snacks or drinks increases.', NULL, NULL, '2025-11-28 16:40:57'),
(81, 4, 'user', 'explain', 'upload file\\587564104_853785123870580_5234701930648061563_n.jpg', 'image/jpeg', '2025-11-28 16:41:30'),
(82, 4, 'chatbot', 'The image presents a document from North South University\'s Office of the Controller of Examinations, detailing the exam period schedule for the academic calendar. The document is divided into two sections, each containing a table with essential information.\n\n*   **First Section:**\n    *   **Table Structure:** The table consists of four columns and four rows.\n    *   **Columns:**\n        *   Class Days\n        *   Last Date & Day of Classes\n        *   Final Exam Date & Day\n        *   Class Time Slot\n    *   **Rows:**\n        *   Row 1: RA, 11 December 2025 Thursday, 18 December 2025 Thursday, 08:00 AM - 09:30 AM, 11:20 AM - 12:50 PM, 02:40 PM - 04:10 PM, 05:00 PM - 06:30 PM*\n        *   Row 2: RA, 11 December 2025 Thursday, 20 December 2025 Saturday, 09:40 AM - 11:10 AM, 01:00 PM - 02:30 PM, 04:20 PM - 05:50 PM\n        *   Row 3: ST, 14 December 2025 Sunday, 21 December 2025 Sunday, 08:00 AM - 09:30 AM, 11:20 AM - 12:50 PM, 02:40 PM - 04:10 PM, 05:00 PM - 06:30 PM*\n    *   **Additional Information:**\n        *   A note at the top requests suggestions or observations by 20 November 2025.\n        *   A footnote indicates that the regular class slot is from 06:00 PM to 07:30 PM.\n*   **Second Section:**\n    *   **Table Structure:** The table consists of four columns and three rows.\n    *   **Columns:**\n        *   Class Days\n        *   Last Date & Day of Classes\n        *   Final Exam Date & Day\n        *   Class Time Slot\n    *   **Rows:**\n        *   Row 1: ST, 14 December 2025 Sunday, 22 December 2025 Monday, 09:40 AM - 11:10 AM, 01:00 PM - 02:30 PM, 04:20 PM - 05:50 PM\n        *   Row 2: MW, 15 December 2025 Monday, 23 December 2025 Tuesday, 08:00 AM - 09:30 AM, 11:20 AM - 12:50 PM, 02:40 PM - 04:10 PM, 05:00 PM - 06:30 PM*\n        *   Row 3: MW, 15 December 2025 Monday, 24 December 2025 Wednesday, 09:40 AM - 11:10 AM, 01:00 PM - 02:30 PM, 04:20 PM - 05:50 PM\n    *   **Additional Information:**\n        *   A request for course instructors to collect information from students with more than three examinations on the same day and report these cases to the respective Chairperson or Program Director for necessary rescheduling.\n\nIn summary, the document provides a detailed schedule for the exam period at North South University, including class days, last dates of classes, final exam dates, and class time slots. It also includes additional information and requests regarding the exam period.', NULL, NULL, '2025-11-28 16:41:34'),
(83, 4, 'user', 'what is that?', 'upload file\\Nayeema.pdf', 'application/pdf', '2025-11-28 16:43:43'),
(84, 4, 'chatbot', 'The attached content appears to be a cover page or submission page for an academic assignment. Specifically, it seems to be for a law student named Nayeema Akter, who is submitting an assignment for a course called \"Legal Drafting (Civil and Criminal) and Conveyance\" (Course Code: Law418) at North South University\'s Department of Law.\n\nThe page includes details such as:\n\n* Course information (code, title, section)\n* Submission date (November 22, 2025)\n* Student\'s name and ID\n* Instructor\'s name (Abu Sufian Md Noman)\n* University and department information\n\nIt\'s likely that the actual assignment content follows this cover page.', NULL, NULL, '2025-11-28 16:43:45'),
(85, 5, 'user', 'hey', NULL, NULL, '2025-11-28 16:52:04'),
(86, 5, 'chatbot', 'It\'s nice to meet you. Is there something I can help you with or would you like to chat?', NULL, NULL, '2025-11-28 16:52:06');

-- --------------------------------------------------------

--
-- Table structure for table `chat_session`
--

CREATE TABLE `chat_session` (
  `session_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `started_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `title` varchar(255) DEFAULT 'New Chat',
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_session`
--

INSERT INTO `chat_session` (`session_id`, `user_id`, `started_at`, `title`, `is_deleted`) VALUES
(4, 38, '2025-11-23 10:25:07', 'New Chat', 0),
(5, 1, '2025-11-23 14:17:18', 'hello', 0),
(6, 19, '2025-11-23 16:25:03', 'New Chat', 0);

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `course_id` int(11) NOT NULL,
  `instructor_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `difficulty` enum('beginner','intermediate','advanced') DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `status` enum('active','inactive','pending') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`course_id`, `instructor_id`, `title`, `description`, `category`, `difficulty`, `price`, `status`) VALUES
(41, 18, '', '', '', '', 0.00, ''),
(62, 16, 'Arz kiysa hai', 'good course', 'Development', 'intermediate', 69.00, 'active'),
(64, 16, 'fsdfsdsdfa', 'sadsdf', 'Design', 'intermediate', 3434.00, 'active'),
(65, 16, 'Where did pi (π) come from?', 'sdadfdf', 'Design', 'beginner', 444.00, 'active'),
(67, 16, 'WEB DEVELOPMENT COMPLETE ROADMAP FOR BEGINNERS', 'sdfssadfsd', 'Development', 'intermediate', 44444.00, 'active'),
(69, 21, 'fuck', 'fuck', 'Development', 'intermediate', 49.99, ''),
(75, 21, 'lalu', 'sss', 'IT and Software', 'advanced', 12.00, 'active'),
(76, 21, 'dfsafsd', 'asadsfadfsadf', 'Development', 'intermediate', 22.00, 'active'),
(77, 16, 'adfsfdssaf', 'asfddfasfsd', 'Development', 'intermediate', 333.00, 'active'),
(78, 16, 'Become a UI/UX designer in 2024 - A step by step guide', 'dfasdfd', 'Development', 'intermediate', 33.00, 'active'),
(79, 16, 'What Professional Software Engineers ACTUALLY Do', 'fafsadfafdsdfa', 'Development', 'intermediate', 333.00, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `course_approval`
--

CREATE TABLE `course_approval` (
  `approval_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `approval_status` enum('approved','rejected','pending') NOT NULL DEFAULT 'pending',
  `approval_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_approval`
--

INSERT INTO `course_approval` (`approval_id`, `admin_id`, `course_id`, `approval_status`, `approval_date`) VALUES
(1, NULL, 69, 'pending', NULL),
(5, 1, 69, 'rejected', '2025-11-20'),
(13, NULL, 75, 'pending', NULL),
(14, 1, 75, '', '2025-11-20'),
(15, NULL, 77, 'pending', NULL),
(16, NULL, 78, 'pending', NULL),
(17, NULL, 79, 'pending', NULL),
(18, 1, 79, '', '2025-12-10'),
(19, 1, 78, '', '2025-12-10'),
(20, 1, 77, '', '2025-12-10');

-- --------------------------------------------------------

--
-- Table structure for table `course_content`
--

CREATE TABLE `course_content` (
  `content_id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `type` enum('video','article','quiz') DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `file_url` varchar(255) DEFAULT NULL,
  `content_duration` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_content`
--

INSERT INTO `course_content` (`content_id`, `course_id`, `type`, `title`, `file_url`, `content_duration`) VALUES
(56, 62, 'video', 'Arz kiysa hai', 'Upload Course/Arz kiya hai (official video)  (1).mp4', '00:05:31'),
(58, 64, 'video', 'Where did pi (π) come from?', 'Upload Course/Arz kiya hai (official video)  (1).mp4', '00:05:31'),
(59, 65, 'video', 'sdadfsfda', 'Upload Course/Arz kiya hai (official video)  (1).mp4', '00:05:31'),
(61, 67, 'video', 'Where did pi (π) come from???????????', 'Upload Course/Arz kiya hai (official video)  (1).mp4', '00:05:31'),
(63, 69, 'video', 'ffff', 'Upload Course/1763618005_Arz kiya hai (official video)  (1).mp4', '00:30:00'),
(69, 75, 'video', 'nice course', 'Upload Course/1763662491_1763658143_Arz kiya hai (official video)  (1).mp4', '11:11:11'),
(70, 76, 'video', 'nice course', 'Upload Course/Become a UIUX designer in 2024 - A step by step guide.mp4', '11:11:11'),
(71, 77, 'video', 'nice course', 'Upload Course/1765324076_Arz kiya hai (official video)  (1).mp4', '11:11:11'),
(72, 78, 'video', 'asfddf', 'Upload Course/1765324098_Arz kiya hai (official video)  (1).mp4', '11:11:11'),
(73, 79, 'video', 'asdfsdsdsf', 'Upload Course/1765324129_Arz kiya hai (official video)  (1).mp4', '11:11:11');

-- --------------------------------------------------------

--
-- Table structure for table `course_reviews`
--

CREATE TABLE `course_reviews` (
  `review_id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `review_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_reviews`
--

INSERT INTO `course_reviews` (`review_id`, `course_id`, `user_id`, `rating`, `comment`, `review_date`) VALUES
(52, 75, 38, 5, 'goooodd', '2025-12-10');

-- --------------------------------------------------------

--
-- Table structure for table `enrollment`
--

CREATE TABLE `enrollment` (
  `enrollment_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `enrollment_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollment`
--

INSERT INTO `enrollment` (`enrollment_id`, `user_id`, `course_id`, `enrollment_date`) VALUES
(56, 76, NULL, '2025-10-24'),
(71, 38, 75, '2025-11-21'),
(72, 19, 62, '2025-11-23'),
(73, 19, 75, '2025-11-25'),
(74, 11, 62, '2025-11-25'),
(75, 38, 67, '2025-11-29');

-- --------------------------------------------------------

--
-- Table structure for table `forum_post`
--

CREATE TABLE `forum_post` (
  `post_id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `post_text` text DEFAULT NULL,
  `post_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forum_post`
--

INSERT INTO `forum_post` (`post_id`, `course_id`, `user_id`, `post_text`, `post_date`) VALUES
(40, 41, 45, 'Discussion for course: ', '2025-02-26'),
(61, 62, 44, 'Discussion for course: Arz kiysa hai', '2025-11-14'),
(63, 64, 44, 'Discussion for course: fsdfsdsdfa', '2025-11-14'),
(64, 65, 44, 'Discussion for course: Where did pi (π) come from?', '2025-11-14'),
(66, 67, 44, 'Discussion for course: WEB DEVELOPMENT COMPLETE ROADMAP FOR BEGINNERS', '2025-11-14'),
(68, 69, 48, 'Discussion for course: fuck', '2025-11-20'),
(74, 75, 48, 'Discussion for course: lalu', '2025-11-20'),
(75, 76, 48, 'Discussion for course: dfsafsd', '2025-12-05'),
(76, 77, 44, 'Discussion for course: adfsfdssaf', '2025-12-10'),
(77, 78, 44, 'Discussion for course: Become a UI/UX designer in 2024 - A step by step guide', '2025-12-10'),
(78, 79, 44, 'Discussion for course: What Professional Software Engineers ACTUALLY Do', '2025-12-10');

-- --------------------------------------------------------

--
-- Table structure for table `instructor`
--

CREATE TABLE `instructor` (
  `instructor_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `expertise` varchar(255) DEFAULT NULL,
  `total_courses` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instructor`
--

INSERT INTO `instructor` (`instructor_id`, `user_id`, `expertise`, `total_courses`) VALUES
(5, 1, NULL, 0),
(16, 44, NULL, 0),
(18, 45, NULL, 0),
(19, 47, NULL, 0),
(20, 45, 'Backend', 0),
(21, 48, NULL, 0),
(22, 47, 'Backend', 0),
(28, 45, 'web', 0),
(35, 84, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `instructor_approval`
--

CREATE TABLE `instructor_approval` (
  `approval_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `approval_status` enum('approved','rejected','pending') DEFAULT NULL,
  `approval_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instructor_approval`
--

INSERT INTO `instructor_approval` (`approval_id`, `admin_id`, `user_id`, `approval_status`, `approval_date`) VALUES
(18, 1, 44, 'approved', '2024-12-05'),
(20, 1, 45, 'approved', '2024-12-05'),
(21, 1, 47, 'approved', '2024-12-05'),
(22, 1, 48, 'approved', '2024-12-05'),
(39, 1, 84, 'approved', '2025-12-10');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `thread_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message_text` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `message_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `thread_id`, `sender_id`, `message_text`, `file_path`, `file_type`, `file_size`, `message_time`, `is_read`) VALUES
(3, 2, 11, 'hey', NULL, NULL, NULL, '2025-11-25 13:52:29', 0),
(5, 8, 38, '', 'uploads/1764088346_575117594_1511414699898216_1357231315854197265_n.jpg', 'jpg', 318225, '2025-11-25 16:32:26', 1),
(6, 8, 11, 'Thank you', NULL, NULL, NULL, '2025-11-25 16:32:45', 1),
(7, 8, 11, 'hey', NULL, NULL, NULL, '2025-11-25 16:42:19', 1),
(8, 8, 11, 'can you here me', NULL, NULL, NULL, '2025-11-25 16:42:29', 1),
(9, 8, 38, 'heyy', NULL, NULL, NULL, '2025-11-26 14:02:18', 1),
(10, 8, 38, '', 'uploads/1764167594_Gemini_Generated_Image_b0833zb0833zb083.png', 'png', 1038847, '2025-11-26 14:33:14', 1),
(11, 8, 38, 'new text', NULL, NULL, NULL, '2025-11-26 14:46:18', 1),
(12, 8, 38, 'send', NULL, NULL, NULL, '2025-11-26 14:46:19', 1),
(13, 8, 38, 'tania', NULL, NULL, NULL, '2025-11-26 14:46:21', 1),
(14, 8, 38, 'can you', NULL, NULL, NULL, '2025-11-26 14:46:23', 1),
(15, 8, 38, '', 'uploads/1764168392_566571579_799816789341739_7174000040483488935_n.jpg', 'jpg', 185953, '2025-11-26 14:46:32', 1),
(16, 12, 38, 'hello', NULL, NULL, NULL, '2025-11-28 16:44:30', 1),
(17, 12, 14, 'hiii how are you?', NULL, NULL, NULL, '2025-11-28 16:45:51', 1),
(18, 12, 38, '', 'uploads/1764348373_587564104_853785123870580_5234701930648061563_n.jpg', 'jpg', 481766, '2025-11-28 16:46:13', 1),
(19, 15, 44, 'heyy', NULL, NULL, NULL, '2025-11-28 16:49:58', 0),
(20, 10, 44, 'hello', NULL, NULL, NULL, '2025-11-28 16:50:09', 1),
(21, 10, 38, 'hey', NULL, NULL, NULL, '2025-11-28 18:24:56', 0);

-- --------------------------------------------------------

--
-- Table structure for table `message_threads`
--

CREATE TABLE `message_threads` (
  `thread_id` int(11) NOT NULL,
  `user1_id` int(11) NOT NULL,
  `user2_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message_threads`
--

INSERT INTO `message_threads` (`thread_id`, `user1_id`, `user2_id`, `created_at`) VALUES
(2, 11, 1, '2025-11-25 13:52:29'),
(5, 38, 56, '2025-11-25 16:30:23'),
(6, 38, 1, '2025-11-25 16:30:27'),
(7, 38, 73, '2025-11-25 16:30:39'),
(8, 11, 38, '2025-11-25 16:31:42'),
(9, 11, 73, '2025-11-25 16:32:34'),
(10, 38, 44, '2025-11-25 16:33:47'),
(11, 38, 48, '2025-11-25 16:42:50'),
(12, 38, 14, '2025-11-25 16:42:51'),
(13, 38, 19, '2025-11-26 14:32:27'),
(14, 38, 65, '2025-11-26 14:32:29'),
(15, 44, 14, '2025-11-28 16:49:54'),
(16, 38, 38, '2025-11-28 16:58:01'),
(17, 44, 44, '2025-11-28 17:05:34');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `user_id`, `course_id`, `amount`, `payment_date`, `transaction_id`) VALUES
(64, 38, 76, 22.00, '0000-00-00', 'txn_6932d13a11230');

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `question_id` int(11) NOT NULL,
  `quiz_id` int(11) DEFAULT NULL,
  `question_text` text NOT NULL,
  `question_type` enum('multiple_choice','true_false','short_answer') NOT NULL,
  `option1` varchar(255) DEFAULT NULL,
  `option2` varchar(255) DEFAULT NULL,
  `option3` varchar(255) DEFAULT NULL,
  `option4` varchar(255) DEFAULT NULL,
  `answer` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`question_id`, `quiz_id`, `question_text`, `question_type`, `option1`, `option2`, `option3`, `option4`, `answer`) VALUES
(1, 37, 'Who is he?', 'multiple_choice', 'amm', 'jam', 'kathal', 'lichu', 'amm'),
(2, 38, 'how are you?', 'true_false', NULL, NULL, NULL, NULL, 'True');

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE `quiz` (
  `quiz_id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `total_questions` int(11) DEFAULT NULL,
  `passing_marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`quiz_id`, `course_id`, `total_questions`, `passing_marks`) VALUES
(26, 65, 3, 15),
(32, 62, 2, 10),
(37, 75, 1, 10),
(38, 76, 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `user_id`) VALUES
(2, 19),
(6, 38),
(13, 56),
(15, 59),
(16, 60),
(20, 65),
(22, 70),
(23, 72),
(24, 73),
(25, 75),
(26, 76);

-- --------------------------------------------------------

--
-- Table structure for table `typing_status`
--

CREATE TABLE `typing_status` (
  `id` int(11) NOT NULL,
  `thread_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_typing` tinyint(1) DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `firstName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('student','instructor','admin') DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `firstName`, `lastName`, `email`, `password`, `role`, `profile_pic`, `bio`, `is_approved`) VALUES
(1, 'Karimul', 'Hassan', 'admin@skillpro.com', '$2y$10$rwV223a8yavMwZoFk1YZ0ePeGoOtQ63rcd9silJdtRIoGpdAPWADy', 'admin', 'images/karimul.jpg', 'Hey I am admin', 0),
(11, 'Tania', 'Akter', 'tania@gmail.com', '$2y$10$K.NEqeworormYJezF98mhenKwWWytg.J43KQyM4PFa8FVevG3Bra6', 'student', 'images/Nikola Tesla.jpg', 'hey', 0),
(14, 'Jhon', 'Cena', 'jhon@gmail.com', '$2y$10$utFeC7JLs23LnfbBYKdP8e.KeOjG2f6Aiv.Knvu.3taGilyJpkr8m', 'student', 'images/jhon cena.jpg', 'jafosadf', 0),
(19, 'kabirul', 'hassan', 'kabirul@gmail.com', '$2y$10$b6IGbaCF7L7XNIqkdC/RM.wYa/hzNhgOlKIRkBkwZleLQUkv2UKIa', 'student', 'images/nobi.jpg', 'hey this is karimul', 0),
(38, 'Md Rafi Mukaddis ', 'Adil Khan', 'adil@gmail.com', '$2y$10$dDJ0V1bFYvZUimO0b7Mgyex8/bJgMk4HgFIzPij02B/3fcApQEqUy', 'student', 'images/adil.jpg', ' Bangladesh German Technical Training Center', 0),
(44, 'Prof. Michaell', 'Anderson', 'michael.anderson@university.edu', '$2y$10$wBwE4dXaDAB6ogsY.Rlv6uOlSE15whC8N6PTUKG8FO25epUN/bo1q', 'instructor', 'images/Prof. Michael Anderson.jpeg', 'Specializes in Software Engineering and Algorithms.', 1),
(45, 'Ms James ', 'Reynoldss', 'james.reynolds@university.edu', '$2y$10$x4fIBLZMcmUhIkDNbDoCn.qq3I6YtxU0zzPrm.pPAfJrtM9BhJ08u', 'instructor', 'images/Mr. James Reynolds.jpg', ' Focused on Networking and Backend Technologies', 1),
(47, 'Ms. Sarah ', 'Thompson', 'sarah.thompson@university.edu', '$2y$10$nDG9kSKODwomXX2Dxa3sgOoXtB078IwMAb3AJOf1VK4N/1kL4f/b6', 'instructor', 'images/Ms. Sarah.jpeg', ' Skilled in UX/UI Design and Frontend Development.', 1),
(48, 'Emily', ' Carter  karimul', 'emily.carter@university.edu', '$2y$10$UY1kgdKpqRjFRXA2VIq60ecow966BrnuvorxxdF6ccRO.0XhfXw0y', 'instructor', 'images/Dr. Emily Carter.jpg', 'Expert in Database Systems and Web Development.', 1),
(56, 'Badhon', 'Badhon', 'Badhon@gmail.com', '$2y$10$edZGHa2iRuigJSHw81.KJeai0SddTJRKWIpLpriM6pXe.Q2lWU.Gm', 'student', 'images/Firefly 20250124164137.png', 'adfsdsf', 0),
(59, 'S', 'HASSAN', 'kaziulhassan66@gmail.com', '$2y$10$AjzaiBAjiDvPBNuhjpMnReIkyevHOLPYE1GI3a1W7.jhh1sq1kcFK', 'student', 'images/0c9f7706c16616cf7b8cb3b4f30d1cce (1).jpeg', 'hey', 0),
(60, 'S.', 'karimul hassan', 'karimul.hassan09@gmail.com', '$2y$10$u82VhhvlL3LZKzAzhlQ0pOQ/p3OLJ5YAbTVUg6qgfFNAtqqx9xAyq', 'student', 'uploads/profile_pics/profile_68e7e1acaa5905.46783352.jpg', 'Hey.......', 0),
(65, 'Iftikhar', 'Ahmed', 'Iftikhar@gmail.com', '$2y$10$GPPtGasTpSdmoYae2m3l9eyk9HURpbHmXwgPbp9MLdACImjSgMKyC', 'student', 'Images/dp.jpg', 'hey', 0),
(70, 'Fatema', 'Islam Kotha', 'kotha@gmail.com', '$2y$10$gfOFOZRkFkICkSjvIESN3.CWT71VwJY75.nIDJDgFizr.pg9L.mWu', 'student', 'uploads/profile_pics/profile_681f2e8e73a0e0.31756218.jpg', 'hello world', 0),
(72, 'kalu', 'kalu', 'siamkalu@gmail.com', '$2y$10$4rHbb5ozPZqY6cwlGnlUDug.EQA2nnv/AvK3f1jqOAt3N75.than.', 'student', 'images/558079881_1345784040270194_932910039729141149_n.jpg', 'siamkalu@gmail.com', 0),
(73, 'alu', 'alu', 'alu@gmai.com', '$2y$10$XVaO2CPqO0fjvvrw/6/T6.50EvnxMFlfEs3wvLRlc3b97hmhUk4E6', 'student', 'images/559075623_1120564706918914_628706393291276535_n.jpg', 'alu@gmai.com', 0),
(75, 'kuttar ', 'The king', 'kutta@gmail.com', '$2y$10$hov2rWL0Wn3/E5E5bzobZOnnhQAgTC5lNDGt9ao.eW0QbyuiHudHG', 'student', 'images/Happy dog.jpg', 'Hey this is a dog', 0),
(76, 'sumi', 'Khan', 'khann@gmail.com', '$2y$10$xyitDYOAKeUgYVjBUaAKHuRCvpVX/lDmV4emX6TyK0PCkHQWhLwey', 'student', 'images/Gemini_Generated_Image_l3nhwwl3nhwwl3nh.png', 'afdfdsasasdfdf', 0),
(84, 'S.', 'Hassan', 'shassan@gmil.com', '$2y$10$LAbGHGHdFIu4rnx6YTYHtuNUWWVv8gWqtGjFWTBA/tPl4acENmeLe', 'instructor', 'images/Happy dog.jpg', 'hey man', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `certificate`
--
ALTER TABLE `certificate`
  ADD PRIMARY KEY (`certificate_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `chat_history`
--
ALTER TABLE `chat_history`
  ADD PRIMARY KEY (`chat_id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `chat_session`
--
ALTER TABLE `chat_session`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`course_id`),
  ADD KEY `instructor_id` (`instructor_id`);

--
-- Indexes for table `course_approval`
--
ALTER TABLE `course_approval`
  ADD PRIMARY KEY (`approval_id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `course_content`
--
ALTER TABLE `course_content`
  ADD PRIMARY KEY (`content_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `course_reviews`
--
ALTER TABLE `course_reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD PRIMARY KEY (`enrollment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `forum_post`
--
ALTER TABLE `forum_post`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `instructor`
--
ALTER TABLE `instructor`
  ADD PRIMARY KEY (`instructor_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `instructor_approval`
--
ALTER TABLE `instructor_approval`
  ADD PRIMARY KEY (`approval_id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `thread_id` (`thread_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indexes for table `message_threads`
--
ALTER TABLE `message_threads`
  ADD PRIMARY KEY (`thread_id`),
  ADD KEY `user1_id` (`user1_id`),
  ADD KEY `user2_id` (`user2_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`quiz_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `typing_status`
--
ALTER TABLE `typing_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `thread_id` (`thread_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `certificate`
--
ALTER TABLE `certificate`
  MODIFY `certificate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `chat_history`
--
ALTER TABLE `chat_history`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `chat_session`
--
ALTER TABLE `chat_session`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `course_approval`
--
ALTER TABLE `course_approval`
  MODIFY `approval_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `course_content`
--
ALTER TABLE `course_content`
  MODIFY `content_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `course_reviews`
--
ALTER TABLE `course_reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `enrollment`
--
ALTER TABLE `enrollment`
  MODIFY `enrollment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `forum_post`
--
ALTER TABLE `forum_post`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `instructor`
--
ALTER TABLE `instructor`
  MODIFY `instructor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `instructor_approval`
--
ALTER TABLE `instructor_approval`
  MODIFY `approval_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `message_threads`
--
ALTER TABLE `message_threads`
  MODIFY `thread_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `quiz_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `typing_status`
--
ALTER TABLE `typing_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `certificate`
--
ALTER TABLE `certificate`
  ADD CONSTRAINT `certificate_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `certificate_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_history`
--
ALTER TABLE `chat_history`
  ADD CONSTRAINT `chat_history_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `chat_session` (`session_id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_session`
--
ALTER TABLE `chat_session`
  ADD CONSTRAINT `chat_session_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `course_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `instructor` (`instructor_id`) ON DELETE CASCADE;

--
-- Constraints for table `course_approval`
--
ALTER TABLE `course_approval`
  ADD CONSTRAINT `course_approval_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_approval_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`) ON DELETE CASCADE;

--
-- Constraints for table `course_content`
--
ALTER TABLE `course_content`
  ADD CONSTRAINT `course_content_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`) ON DELETE CASCADE;

--
-- Constraints for table `course_reviews`
--
ALTER TABLE `course_reviews`
  ADD CONSTRAINT `course_reviews_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD CONSTRAINT `enrollment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollment_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`) ON DELETE CASCADE;

--
-- Constraints for table `forum_post`
--
ALTER TABLE `forum_post`
  ADD CONSTRAINT `forum_post_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forum_post_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `instructor`
--
ALTER TABLE `instructor`
  ADD CONSTRAINT `instructor_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `instructor_approval`
--
ALTER TABLE `instructor_approval`
  ADD CONSTRAINT `instructor_approval_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `instructor_approval_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`thread_id`) REFERENCES `message_threads` (`thread_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `message_threads`
--
ALTER TABLE `message_threads`
  ADD CONSTRAINT `message_threads_ibfk_1` FOREIGN KEY (`user1_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `message_threads_ibfk_2` FOREIGN KEY (`user2_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`) ON DELETE CASCADE;

--
-- Constraints for table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`quiz_id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz`
--
ALTER TABLE `quiz`
  ADD CONSTRAINT `quiz_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`) ON DELETE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `typing_status`
--
ALTER TABLE `typing_status`
  ADD CONSTRAINT `typing_status_ibfk_1` FOREIGN KEY (`thread_id`) REFERENCES `message_threads` (`thread_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `typing_status_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

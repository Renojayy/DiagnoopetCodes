-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2025 at 05:06 PM
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
-- Database: `diagnopet`
--

-- --------------------------------------------------------

--
-- Table structure for table `ai_assistant`
--

CREATE TABLE `ai_assistant` (
  `id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Version` varchar(50) NOT NULL,
  `AI Pre Table` varchar(255) NOT NULL,
  `Response Time` varchar(50) NOT NULL,
  `Knowledge Base` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ai_diagnoses`
--

CREATE TABLE `ai_diagnoses` (
  `diagnosis_id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL,
  `submission_uuid` varchar(36) NOT NULL,
  `datetime_recorded` datetime NOT NULL DEFAULT current_timestamp(),
  `ai_prediagnosis` text NOT NULL,
  `actions_recommended` text NOT NULL,
  `severity_level` varchar(50) NOT NULL,
  `possible_conditions` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ai_diagnoses`
--

INSERT INTO `ai_diagnoses` (`diagnosis_id`, `pet_id`, `submission_uuid`, `datetime_recorded`, `ai_prediagnosis`, `actions_recommended`, `severity_level`, `possible_conditions`) VALUES
(40, 129, 'b72b66b2-d220-11f0-a3a2-9883891fe8c4', '2025-12-06 05:24:00', 'The symptoms of lethargy, loss of appetite, and nasal discharge in a 3-year-old underweight dog (4.00 kg) suggest a possible systemic infection or respiratory illness. The combination of symptoms could indicate conditions ranging from an upper respiratory infection to more serious issues like pneumonia or viral diseases. Immediate veterinary consultation is essential, as low body weight increases vulnerability to complications.', 'Monitor respiratory rate, check for fever (warm ears/paws), ensure hydration, and isolate from other pets. Track any changes in nasal discharge color or consistency. Seek veterinary care within 24 hours.', 'High', 'Upper Respiratory Infection, Canine Distemper, Pneumonia, Sinusitis'),
(41, 129, 'b4b54eb1-d229-11f0-ba1b-9883891fe8c4', '2025-12-06 06:28:21', 'The combination of lethargy, appetite loss, and nasal discharge in a 4kg dog suggests a possible respiratory infection or systemic illness. Given Irl\'s small size, even mild symptoms can escalate rapidly. This requires prompt veterinary evaluation to rule out conditions like pneumonia, viral infections, or metabolic disorders. Always consult a licensed veterinarian for accurate diagnosis and treatment.', 'Monitor respiratory rate and effort, check gum color (should be pink), ensure hydration, and track food/water intake. Keep Irl warm and isolated from other pets. Seek veterinary care within 24 hours.', 'High', 'Upper Respiratory Infection, Canine Distemper, Pneumonia, Rhinitis, Systemic Viral Infection'),
(46, 129, '21f9231c-d23c-11f0-bfe9-9883891fe8c4', '2025-12-06 01:40:08', 'Based on the symptoms of lethargy, loss of appetite, and nasal discharge, Irl may be suffering from an upper respiratory infection or a systemic illness. These symptoms can indicate conditions ranging from kennel cough to more serious diseases like distemper. It is crucial to consult a licensed veterinarian promptly for an accurate diagnosis and treatment. Note: Loss of appetite in a small dog can lead to rapid deterioration.', 'Monitor Irl\'s hydration and food intake closely. Offer water frequently and try enticing with wet food or broth. Keep the dog warm and limit activity. Note any changes in nasal discharge (color, consistency) or new symptoms (coughing, vomiting, diarrhea). Avoid contact with other pets to prevent potential spread of infection.', 'Moderate', 'Upper Respiratory Infection, Canine Distemper, Pneumonia, Dental Disease'),
(47, 131, '4d2fdeb5-d24e-11f0-9824-9883891fe8c4', '2025-12-06 03:50:12', 'Gats is exhibiting lethargy, appetite loss, and nasal discharge at 6 months old with very low weight (1 kg). This combination suggests possible infection, respiratory illness, or systemic disease. Puppies are especially vulnerable to rapid deterioration. URGENT veterinary evaluation is required to rule out life-threatening conditions like parvovirus or distemper. Always consult a licensed veterinarian immediately for serious symptoms.', '1. Seek emergency vet care today 2. Isolate from other pets 3. Monitor temperature, breathing, and gum color 4. Offer small amounts of water 5. Do not administer medications without vet guidance', 'Critical', 'Canine Parvovirus, Distemper, Upper Respiratory Infection, Pneumonia, Systemic Bacterial Infection'),
(48, 131, 'e4008374-d24e-11f0-9824-9883891fe8c4', '2025-12-06 03:54:20', 'Gats, a 6-month-old puppy, is exhibiting lethargy, loss of appetite, and nasal discharge. The extremely low weight (1 kg) is a critical concern. These symptoms could indicate a serious infectious disease such as canine distemper or a severe respiratory infection. Malnutrition or parasitic infection may also be contributing. This is an emergency situation. You must consult a licensed veterinarian immediately.', 'Seek emergency veterinary care immediately. Until seen, keep the puppy warm and offer a small amount of honey or sugar water on the gums if weak to combat hypoglycemia. Do not force-feed if unresponsive.', 'Critical', 'Canine Distemper, Upper Respiratory Infection, Malnutrition, Parasitic Infection'),
(49, 131, '113dbd94-d251-11f0-9824-9883891fe8c4', '2025-12-06 04:10:02', 'Gats, a 6-month-old underweight dog (1.00 kg), shows lethargy, appetite loss, and nasal discharge. These symptoms suggest possible infection, respiratory illness, or systemic disease. Given the puppy\'s age and critically low weight, this requires urgent veterinary evaluation to rule out life-threatening conditions like parvovirus, distemper, or severe malnutrition. Always consult a licensed veterinarian immediately for serious symptoms.', '1) Ensure hydration with small water amounts. 2) Monitor temperature (normal: 100-102.5°F). 3) Isolate from other pets. 4) Track any vomiting/diarrhea. 5) Seek emergency vet care within hours.', 'Critical', 'Canine Distemper, Parvovirus, Upper Respiratory Infection, Pneumonia, Malnutrition, Parasitic Infestation'),
(50, 131, '5d36b650-d251-11f0-9824-9883891fe8c4', '2025-12-06 04:12:09', 'Gats, a 6-month-old underweight dog (1.00 kg), shows lethargy, appetite loss, and nasal discharge. These symptoms suggest a possible systemic infection or respiratory illness. Puppies are vulnerable to rapid deterioration, so immediate veterinary consultation is essential for accurate diagnosis and treatment.', 'Monitor temperature, hydration (check gum moisture), and breathing patterns. Offer small amounts of water frequently. Isolate from other pets. Seek emergency vet care if breathing difficulties, vomiting, or diarrhea develop.', 'Critical', 'Canine Distemper, Parvovirus, Upper Respiratory Infection, Pneumonia, Systemic Infection'),
(51, 131, '7fc3851e-d251-11f0-9824-9883891fe8c4', '2025-12-06 04:13:07', 'Gats, a 6-month-old dog weighing only 1.00 kg, is showing lethargy, loss of appetite, and nasal discharge. These symptoms in a young, underweight puppy are concerning and could indicate a serious condition such as an infection (viral or bacterial), parasitic infestation, or even canine distemper. Given the severity of possible conditions and Gats\' vulnerable state, it is critical to seek immediate veterinary care. Remember, only a licensed veterinarian can provide a definitive diagnosis and appropriate treatment.', '1. Seek immediate veterinary attention. 2. Monitor for fever, coughing, vomiting, diarrhea, or changes in breathing. 3. Keep Gats warm, quiet, and isolated from other pets. 4. Encourage hydration by offering water or ice cubes. Do not force-feed.', 'Critical', 'Canine Distemper, Upper Respiratory Infection, Parasitic Infection'),
(52, 132, '4d48cefb-d2a0-11f0-a1d4-9883891fe8c4', '2025-12-06 16:36:56', 'No symptoms were provided for Gats. Without symptoms, it is impossible to assess any health concerns. Please monitor your pet closely and consult a licensed veterinarian if you observe any abnormal behavior or symptoms.', 'Observe Gats for any signs of illness such as changes in appetite, energy levels, vomiting, diarrhea, coughing, sneezing, or unusual behavior. If symptoms appear, note them and contact your veterinarian.', 'Low', 'None reported');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `consultation_id` int(11) NOT NULL,
  `Pet Owner` varchar(100) NOT NULL,
  `Veterinary License Number` varchar(255) DEFAULT NULL,
  `Consultations Date` date NOT NULL,
  `Symptoms Discussed` varchar(255) NOT NULL,
  `Remarks` varchar(255) NOT NULL,
  `Level of Threats` varchar(50) NOT NULL,
  `Pet` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`consultation_id`, `Pet Owner`, `Veterinary License Number`, `Consultations Date`, `Symptoms Discussed`, `Remarks`, `Level of Threats`, `Pet`) VALUES
(2, 'Euvie Test', '12345', '2025-12-06', 'sdfsfsf', 'sfdfsf', 'High', '129'),
(10, 'mcdavid', '43424', '2025-12-04', 'Shesshh', 'Random', 'Medium', ''),
(19, 'Brownie', '545353', '2025-12-04', 'fever', 'random', 'High', ''),
(434343, 'Euvie Test', '12345', '2025-12-06', 'hahahah', 'ahahaha', 'Low', '129');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_name` varchar(255) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_type` enum('petowner','vet') NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `sent_date` datetime DEFAULT current_timestamp(),
  `sender_type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_name`, `sender_id`, `receiver_type`, `receiver_id`, `subject`, `message`, `status`, `sent_date`, `sender_type`) VALUES
(26, 'Euvie Test', 3, 'vet', 26, '', 'yo', 'unread', '2025-12-05 23:28:41', 'petowner'),
(27, 'Euvie Test', 3, 'vet', 26, '', 'yo', 'unread', '2025-12-05 23:32:08', 'petowner'),
(28, 'Euvie Test', 3, 'petowner', 3, '', 'yo', 'unread', '2025-12-05 23:32:56', 'petowner'),
(29, 'Euvie Test', 3, 'petowner', 3, '', 'yo', 'unread', '2025-12-05 23:35:31', 'petowner'),
(30, 'Euvie Test', 3, 'vet', 26, '', 'yo', 'unread', '2025-12-05 23:35:42', 'petowner'),
(31, 'Euvie Test', 3, 'petowner', 3, '', 'test', 'unread', '2025-12-05 23:36:03', 'petowner'),
(32, 'Euvie Test', 3, 'vet', 26, '', 'yo', 'unread', '2025-12-05 23:56:36', 'petowner'),
(33, 'Test Vet', 26, 'petowner', 3, '', 'yo', 'unread', '2025-12-06 00:05:46', 'vet'),
(34, 'Euvie Test', 3, 'vet', 26, '', 'yo', 'unread', '2025-12-06 00:13:03', 'petowner'),
(35, 'Euvie Test', 3, 'vet', 26, '', 'yo', 'unread', '2025-12-06 00:16:29', 'petowner'),
(36, 'Test Vet', 26, 'petowner', 3, '', 'yes', 'unread', '2025-12-06 00:36:00', 'vet'),
(37, 'Test Vet', 26, 'petowner', 3, '', 'renojey', 'unread', '2025-12-06 00:36:23', 'vet'),
(38, 'Euvie Test', 3, 'vet', 26, '', 'yes', 'unread', '2025-12-06 00:36:52', 'petowner'),
(39, 'Euvie Test', 3, 'vet', 26, '', 'ara na pre', 'unread', '2025-12-06 00:37:01', 'petowner'),
(40, 'Euvie Test', 3, 'vet', 26, '', 'pre', 'unread', '2025-12-06 00:38:24', 'petowner'),
(41, 'Euvie Test', 3, 'vet', 26, '', 'pre', 'unread', '2025-12-06 00:38:51', 'petowner'),
(42, 'Euvie Test', 3, 'vet', 26, '', 'yut', 'unread', '2025-12-06 00:52:39', 'petowner'),
(43, 'Test Vet', 26, 'petowner', 3, '', 'fg', 'unread', '2025-12-06 01:09:54', 'vet'),
(44, 'Euvie Test', 3, 'vet', 26, '', 'yryryr', 'unread', '2025-12-06 01:10:17', 'petowner'),
(45, 'Test Vet', 26, 'petowner', 3, '', 'asdsa', 'unread', '2025-12-06 01:10:51', 'vet'),
(46, 'Test Vet', 26, 'petowner', 3, '', 'g', 'unread', '2025-12-06 01:34:02', 'vet'),
(47, 'Test Vet', 26, 'petowner', 3, '', 'g', 'unread', '2025-12-06 01:34:05', 'vet'),
(48, 'Test Vet', 26, 'petowner', 3, '', 'hi', 'unread', '2025-12-06 01:34:20', 'vet'),
(49, 'Test Vet', 26, 'petowner', 3, '', 'hi', 'unread', '2025-12-06 01:34:23', 'vet'),
(50, 'Test Vet', 26, 'petowner', 3, '', 'asdsadas', 'unread', '2025-12-06 01:34:29', 'vet'),
(51, 'Test Vet', 26, 'petowner', 3, '', 'asd', 'unread', '2025-12-06 01:34:30', 'vet'),
(52, 'Test Vet', 26, 'petowner', 3, '', 'asdasddas', 'unread', '2025-12-06 01:34:32', 'vet'),
(53, 'Test Vet', 26, 'petowner', 3, '', 'asdasdas', 'unread', '2025-12-06 01:34:33', 'vet'),
(54, 'Test Vet', 26, 'petowner', 3, '', 'asdsadas', 'unread', '2025-12-06 01:34:34', 'vet'),
(55, 'Test Vet', 26, 'petowner', 3, '', 'asdasdas', 'unread', '2025-12-06 01:34:48', 'vet'),
(56, 'Test Vet', 26, 'petowner', 3, '', 'asdasdas', 'unread', '2025-12-06 01:34:49', 'vet'),
(57, 'Test Vet', 26, 'petowner', 3, '', 'asdsadas', 'unread', '2025-12-06 01:34:57', 'vet'),
(58, 'Euvie Test', 3, 'vet', 26, '', 'asas', 'unread', '2025-12-06 01:49:44', 'petowner'),
(59, 'Euvie Test', 3, 'vet', 26, '', 'asdasd', 'unread', '2025-12-06 01:49:46', 'petowner'),
(60, 'Euvie Test', 3, 'vet', 26, '', 'asdasdas', 'unread', '2025-12-06 01:49:48', 'petowner'),
(61, 'Test Vet', 26, 'petowner', 3, '', 'asdsadas', 'unread', '2025-12-06 01:50:31', 'vet'),
(62, 'Test Vet', 26, 'petowner', 3, '', 'gggg', 'unread', '2025-12-06 01:50:33', 'vet'),
(63, 'Test Vet', 26, 'petowner', 3, '', 'asdasd', 'unread', '2025-12-06 01:55:12', 'vet'),
(64, 'Euvie Test', 3, 'vet', 26, '', 'asdadsasd', 'unread', '2025-12-06 01:55:14', 'petowner'),
(65, 'Euvie Test', 3, 'vet', 26, '', 'asdasd', 'unread', '2025-12-06 01:55:16', 'petowner'),
(66, 'Test Vet', 26, 'petowner', 3, '', 'asdasdasd', 'unread', '2025-12-06 01:55:18', 'vet'),
(67, 'Euvie Test', 3, 'vet', 26, '', 'asdasddas', 'unread', '2025-12-06 01:55:20', 'petowner'),
(68, 'Test Vet', 26, 'petowner', 3, '', 'asdasddas', 'unread', '2025-12-06 01:55:22', 'vet'),
(69, 'Euvie Test', 3, 'vet', 26, '', 'asdsaddsaasdasd', 'unread', '2025-12-06 01:55:24', 'petowner'),
(70, 'Test Vet', 26, 'petowner', 3, '', 'asasdasdasdasd', 'unread', '2025-12-06 01:55:26', 'vet'),
(71, 'Euvie Test', 3, 'vet', 26, '', 'asdasdasdasdasd', 'unread', '2025-12-06 01:55:28', 'petowner'),
(72, 'Test Vet', 26, 'petowner', 3, '', 'asfasfafaf', 'unread', '2025-12-06 01:55:32', 'vet'),
(73, 'Euvie Test', 3, 'vet', 26, '', 'afaffafafaaffa', 'unread', '2025-12-06 01:55:34', 'petowner'),
(74, 'Test Vet', 26, 'petowner', 3, '', 'asdasda', 'unread', '2025-12-06 01:55:53', 'vet'),
(75, 'Euvie Test', 3, 'vet', 26, '', 'asdasasdd', 'unread', '2025-12-06 01:55:55', 'petowner'),
(76, 'Euvie Test', 3, 'vet', 26, '', 'asdasdasda', 'unread', '2025-12-06 01:55:57', 'petowner'),
(77, 'Test Vet', 26, 'petowner', 3, '', 'asdasddasda', 'unread', '2025-12-06 01:55:59', 'vet'),
(78, 'Euvie Test', 3, 'vet', 26, '', 'asdasdasda', 'unread', '2025-12-06 01:56:01', 'petowner'),
(79, 'Euvie Test', 3, 'vet', 26, '', 'asdasdasdas', 'unread', '2025-12-06 01:56:03', 'petowner'),
(80, 'Test Vet', 26, 'petowner', 3, '', 'asdasdsadas', 'unread', '2025-12-06 01:56:05', 'vet'),
(81, 'Euvie Test', 3, 'vet', 26, '', 'asdasdaadds', 'unread', '2025-12-06 01:56:07', 'petowner'),
(82, 'Test Vet', 26, 'petowner', 3, '', 'asdasdad', 'unread', '2025-12-06 01:56:10', 'vet'),
(83, 'Euvie Test', 3, 'vet', 26, '', 'adadadada', 'unread', '2025-12-06 01:56:12', 'petowner'),
(84, 'Euvie Test', 3, 'vet', 26, '', 'adadadada', 'unread', '2025-12-06 01:56:14', 'petowner'),
(85, 'Euvie Test', 3, 'vet', 26, '', 'error', 'unread', '2025-12-06 01:57:15', 'petowner'),
(86, 'Test Vet', 26, 'petowner', 3, '', 'no error', 'unread', '2025-12-06 01:57:21', 'vet'),
(87, 'Test Vet', 26, 'petowner', 3, '', 'hey', 'unread', '2025-12-06 01:58:25', 'vet'),
(88, 'Euvie Test', 3, 'vet', 26, '', 'hi', 'unread', '2025-12-06 01:58:32', 'petowner'),
(89, 'Euvie Test', 3, 'vet', 26, '', 'hi', 'unread', '2025-12-06 01:59:28', 'petowner'),
(90, 'Test Vet', 26, 'petowner', 3, '', 'hi', 'unread', '2025-12-06 01:59:30', 'vet'),
(91, 'Test Vet', 26, 'petowner', 3, '', 'hi', 'unread', '2025-12-06 05:25:13', 'vet'),
(92, 'Euvie Test', 3, 'vet', 26, '', 'genki', 'unread', '2025-12-06 05:25:29', 'petowner'),
(93, 'Test Vet', 26, 'petowner', 3, '', 'david', 'unread', '2025-12-06 08:43:24', 'vet'),
(94, 'Euvie Test', 3, 'vet', 26, '', 'early', 'unread', '2025-12-06 08:43:32', 'petowner');

-- --------------------------------------------------------

--
-- Table structure for table `petowners`
--

CREATE TABLE `petowners` (
  `owner_id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `ContactNo` varchar(20) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `petowners`
--

INSERT INTO `petowners` (`owner_id`, `Name`, `ContactNo`, `Password`, `Email`, `Address`) VALUES
(3, 'Euvie Test', '090909090', '$2y$10$AOmmUic67mV7z0rApxpZ9e.XWxhHsz.vJTu2AA0ynPvEDgsDG64te', 'euvie@test.com', 'Talisay City'),
(10, 'John Dabid', '909090', '$2y$10$RYXoFnrqp.LznlaSgUrB3eLH21IYWNkxSWVJ8Eil3/1YTRwKAJtxm', 'johndabid@test.com', 'Bacolod City'),
(11, 'John Rey Gatilogo', '09123456789', '$2y$10$UyKs6v3AgGAvRGDxCTOaIOWC2eHmm3mkZw0EMCA0uTw/ncCGwpaCy', 'janrey@test.com', 'Colegio San Agustin Bacolod');

-- --------------------------------------------------------

--
-- Table structure for table `pets`
--

CREATE TABLE `pets` (
  `pet_id` int(11) NOT NULL,
  `pet_type` varchar(255) DEFAULT NULL,
  `pet_name` varchar(255) DEFAULT NULL,
  `pet_gender` varchar(50) DEFAULT NULL,
  `pet_weight` decimal(5,2) DEFAULT NULL,
  `pet_breed` varchar(255) DEFAULT NULL,
  `pet_age` varchar(50) DEFAULT NULL,
  `pet_symptoms` varchar(255) NOT NULL,
  `owner_name` varchar(100) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `user_name` varchar(100) NOT NULL,
  `history` text DEFAULT NULL,
  `medication` text DEFAULT NULL,
  `prescription` text DEFAULT NULL,
  `vet_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pets`
--

INSERT INTO `pets` (`pet_id`, `pet_type`, `pet_name`, `pet_gender`, `pet_weight`, `pet_breed`, `pet_age`, `pet_symptoms`, `owner_name`, `avatar`, `user_name`, `history`, `medication`, `prescription`, `vet_id`) VALUES
(118, 'Dog', 'Qwerty', 'Male', 2.00, 'Beagle', '2', 'Fever', '', NULL, 'John Dabid', NULL, NULL, NULL, NULL),
(129, 'Dog', 'Irl', 'Male', 4.00, 'Rottweiler', '3', 'Lethargy / Weakness, Loss of appetite, Nasal discharge', '', NULL, 'Euvie Test', NULL, NULL, NULL, NULL),
(131, 'Dog', 'Gats', 'Male', 1.00, 'Dachshund', '6 Months', 'Lethargy / Weakness, Loss of appetite, Nasal discharge', '', 'Gats.jpg', 'John Rey Gatilogo', 'Random', 'Radnom med', 'Random pres', '31'),
(132, '', 'Gats', 'Female', 2.00, 'shih tzu', '1', '', '', 'Gats.png', 'Euvie Test', NULL, NULL, NULL, '26'),
(133, '', 'Gatzz', 'Female', 2.00, 'shih tzu', '2', '', '', 'Gatzz_1765036903.png', 'Euvie Test', NULL, NULL, NULL, NULL),
(134, '', 'Dabid', 'Male', 2.00, 'shih tzu', '2', '', '', 'Dabid_1765036946.jfif', 'Euvie Test', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pets_backup`
--

CREATE TABLE `pets_backup` (
  `pet_id` int(11) NOT NULL DEFAULT 0,
  `Pet Type` varchar(255) NOT NULL,
  `Pet Name` varchar(255) NOT NULL,
  `Pet Gender` varchar(50) NOT NULL,
  `Pet Weight` decimal(5,2) NOT NULL,
  `Pet Breed` varchar(255) NOT NULL,
  `Pet Age` varchar(50) NOT NULL,
  `pet_symptoms` varchar(255) NOT NULL,
  `owner_name` varchar(100) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `user_name` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `age` int(11) NOT NULL,
  `weight` decimal(5,2) NOT NULL,
  `breed` varchar(100) NOT NULL,
  `symptoms` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `prescription` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pets_backup`
--

INSERT INTO `pets_backup` (`pet_id`, `Pet Type`, `Pet Name`, `Pet Gender`, `Pet Weight`, `Pet Breed`, `Pet Age`, `pet_symptoms`, `owner_name`, `avatar`, `user_name`, `type`, `name`, `gender`, `age`, `weight`, `breed`, `symptoms`, `photo`, `created_at`, `prescription`) VALUES
(71, 'Dog', 'Dabid', 'Male', 5.00, 'Boxer', '2', 'Loss of appetite', '', 'Dabid.jfif', 'Euvie Test', '', '', 'Male', 0, 0.00, '', '3x a day', NULL, '2025-11-30 15:43:45', 'Bioflu'),
(72, 'Dog', 'Genkit', 'Male', 3.00, 'Beagle', '3', 'Difficulty breathing', '', 'Genkit.jfif', 'Euvie Test', '', '', 'Male', 0, 0.00, '', NULL, NULL, '2025-11-30 16:27:55', NULL),
(73, 'Dog', 'Dabid', 'Male', 2.00, 'Boxer', '2', 'Eye discharge', '', 'Dabid.jfif', 'Euvie Test', '', '', 'Male', 0, 0.00, '', NULL, NULL, '2025-11-30 16:32:32', NULL),
(74, 'Dog', 'xxsa', 'Male', 3.00, 'Yorkshire Terrier', '2', 'Loss of appetite', '', 'xxsa.jfif', 'Euvie Test', '', '', 'Male', 0, 0.00, '', NULL, NULL, '2025-11-30 16:32:51', NULL),
(75, 'Dog', 'Renojayy', 'Male', 2.00, 'Maltese', '2', 'Fever', '', 'Renojayy.jfif', 'Euvie Test', '', '', 'Male', 0, 0.00, '', NULL, NULL, '2025-11-30 16:35:14', NULL),
(76, 'Cat', 'Cat', 'Male', 2.00, 'Burmese', '2', 'Nasal discharge', '', 'Cat.jfif', 'Euvie Test', '', '', 'Male', 0, 0.00, '', NULL, NULL, '2025-11-30 16:35:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `rating_id` int(11) NOT NULL,
  `vet_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`rating_id`, `vet_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(1, 26, 3, 2, NULL, '2025-12-01 17:01:53'),
(2, 26, 3, 3, NULL, '2025-12-01 17:33:02'),
(3, 26, 3, 3, NULL, '2025-12-01 17:36:44'),
(4, 26, 3, 4, NULL, '2025-12-01 18:17:41'),
(5, 26, 3, 3, NULL, '2025-12-01 18:19:21'),
(6, 26, 3, 4, NULL, '2025-12-01 18:19:32'),
(7, 26, 3, 4, NULL, '2025-12-04 03:06:55'),
(8, 27, 3, 5, NULL, '2025-12-04 03:06:59'),
(9, 26, 3, 2, NULL, '2025-12-06 00:42:56');

-- --------------------------------------------------------

--
-- Table structure for table `symptoms`
--

CREATE TABLE `symptoms` (
  `id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL,
  `symptom` text NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `symptoms`
--

INSERT INTO `symptoms` (`id`, `pet_id`, `symptom`, `date_added`, `user_name`) VALUES
(133, 118, 'Fever', '2025-12-04 16:40:50', 'John Dabid'),
(154, 129, 'Lethargy / Weakness', '2025-12-05 21:23:53', 'Euvie Test'),
(155, 129, 'Loss of appetite', '2025-12-05 21:23:53', 'Euvie Test'),
(156, 129, 'Nasal discharge', '2025-12-05 21:23:53', 'Euvie Test'),
(160, 131, 'Lethargy / Weakness', '2025-12-06 02:49:50', 'John Rey Gatilogo'),
(161, 131, 'Loss of appetite', '2025-12-06 02:49:50', 'John Rey Gatilogo'),
(162, 131, 'Nasal discharge', '2025-12-06 02:49:50', 'John Rey Gatilogo'),
(163, 131, 'Foul skin odor', '2025-12-06 03:11:58', 'John Rey Gatilogo');

-- --------------------------------------------------------

--
-- Table structure for table `veterinarian`
--

CREATE TABLE `veterinarian` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `specialization` text NOT NULL,
  `license_number` varchar(255) NOT NULL,
  `expiration_date` date DEFAULT NULL,
  `prc_id_path` varchar(255) DEFAULT NULL,
  `verification_status` enum('verified','not_verified') DEFAULT 'not_verified',
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `clinic_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `registration_time` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `clinic_address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `veterinarian`
--

INSERT INTO `veterinarian` (`id`, `name`, `specialization`, `license_number`, `expiration_date`, `prc_id_path`, `verification_status`, `password`, `email`, `clinic_name`, `created_at`, `registration_time`, `status`, `clinic_address`, `city`, `latitude`, `longitude`) VALUES
(26, 'Test Vet', 'Ophthalmology', '12345', '2025-11-29', 'uploads/images/test_vet.jpg', 'not_verified', '$2y$10$TOXZpYP2COeMnqHIPURWl.fYfQhoSXNBs3UjlZAVBPRT6lgJPDT2C', 'testvet@test.com', 'Mandalagan vet', '2025-11-29 02:06:44', '2025-11-29 10:06:44', 'approved', 'Mandalagan', 'Bacolod', 10.7301854, 122.5591148),
(27, 'Mark bisan', 'Canine and Feline Practice', '123231132123', '2025-12-01', NULL, 'verified', '$2y$10$0a/PFMAK9gLhpK2iBghyk.8fkrHI7CNUEeMC2WiPt9tzw.isn5iZ.', 'test@gmail.com', 'Pahanocoy Vet', '2025-12-01 03:09:40', '2025-12-01 11:09:40', 'approved', 'Negros Occidental', 'Victorias City', 10.7048622, 122.9619251),
(29, 'John Jayy', 'Cardiology', '43424', '2025-12-04', 'uploads/images/john_jayy.jpg', 'verified', '$2y$10$QKdCXoMKWmpkuRvRvAeO1OsSGYTQMwpNxXUqVEJ2Y67M4qVPI6aIO', 'johnjayy@test.com', 'Talisay Vets', '2025-12-04 04:54:31', '2025-12-04 12:54:31', 'approved', 'Talisay City', 'Talisay City', 9.9778560, 124.3381760),
(30, 'Reno Bed', 'Companion Animal Practice', '545353', '2026-01-01', 'uploads/images/reno_bed.png', 'not_verified', '$2y$10$uUOzNsscZ9kpC5ZVeBRWsuEfomVEghEx.3LScX1jEvATVTovvtsz.', 'reno@test.com', 'Victorias Vet', '2025-12-04 12:22:49', '2025-12-04 20:22:49', 'approved', 'Victorias City', 'Victorias City', 10.9136392, 123.0071285),
(31, 'John Mark', 'Companion Animal Practice', '12345', '2025-12-06', 'uploads/images/john_mark.jpg', 'not_verified', '$2y$10$X29MUvLjvANRtwq1u9JX7.PxotxDeS2Z5mu0GIcUElBpB3u8pnwJ.', 'johnmark@test.com', 'Bacolod Vets', '2025-12-06 02:59:22', '2025-12-06 10:59:22', 'approved', 'Bacolod City', 'Victorias City', 10.6814514, 122.9580046);

--
-- Triggers `veterinarian`
--
DELIMITER $$
CREATE TRIGGER `after_vet_insert` AFTER INSERT ON `veterinarian` FOR EACH ROW BEGIN
    INSERT INTO veterinaryclinics (
        veterinarian_id,
        `Clinic Name`,
        Address,
        City,
        Latitude,
        Longitude
    ) VALUES (
        NEW.id,
        NEW.clinic_name,
        NEW.clinic_address,
        NEW.city,
        NEW.latitude,
        NEW.longitude
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `veterinaryclinics`
--

CREATE TABLE `veterinaryclinics` (
  `clinic_id` int(11) NOT NULL,
  `Clinic Name` varchar(150) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `City` varchar(100) NOT NULL,
  `Contact Number` varchar(50) NOT NULL,
  `Latitude` decimal(10,7) NOT NULL,
  `Longitude` decimal(10,7) NOT NULL,
  `Services Offered` varchar(255) DEFAULT NULL,
  `Opening Hours` varchar(150) DEFAULT NULL,
  `veterinarian_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `veterinaryclinics`
--

INSERT INTO `veterinaryclinics` (`clinic_id`, `Clinic Name`, `Address`, `City`, `Contact Number`, `Latitude`, `Longitude`, `Services Offered`, `Opening Hours`, `veterinarian_id`) VALUES
(1, 'Juan Veterinarian', 'Burgos Extension, Bacolod, 6100 Negros Occidental', 'Bacolod City', '', 10.6858553, 122.9817807, NULL, NULL, 24),
(2, 'Talisay Vets', 'Talisay City', 'Talisay City', '', 10.7301854, 122.5591148, NULL, NULL, 25),
(3, 'Mandalagan vet', 'Mandalagan', 'Bacolod', '', 10.7301854, 122.5591148, NULL, NULL, 26),
(4, 'Pahanocoy Vet', 'Negros Occidental', 'Victorias City', '', 10.7048622, 122.9619251, NULL, NULL, 27),
(5, 'Silay Clinic', 'Silay', 'Silay', '', 10.7920983, 122.9748766, NULL, NULL, 28),
(6, 'Talisay Vets', 'Talisay City', 'Talisay City', '', 9.9778560, 124.3381760, NULL, NULL, 29),
(7, 'Victorias Vet', 'Victorias City', 'Victorias City', '', 10.9136392, 123.0071285, NULL, NULL, 30),
(8, 'Bacolod Vets', 'Bacolod City', 'Bacolod City', '', 10.6814514, 122.9580046, NULL, NULL, 31);

-- --------------------------------------------------------

--
-- Table structure for table `vet_petowner_association`
--

CREATE TABLE `vet_petowner_association` (
  `association_id` int(11) NOT NULL,
  `vet_id` int(11) NOT NULL,
  `petowner_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vet_petowner_association`
--

INSERT INTO `vet_petowner_association` (`association_id`, `vet_id`, `petowner_id`, `created_at`) VALUES
(1, 26, 3, '2025-12-05 15:51:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ai_assistant`
--
ALTER TABLE `ai_assistant`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ai_diagnoses`
--
ALTER TABLE `ai_diagnoses`
  ADD PRIMARY KEY (`diagnosis_id`),
  ADD UNIQUE KEY `uq_submission_uuid` (`submission_uuid`),
  ADD KEY `pet_id` (`pet_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`consultation_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `petowners`
--
ALTER TABLE `petowners`
  ADD PRIMARY KEY (`owner_id`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`pet_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`rating_id`);

--
-- Indexes for table `symptoms`
--
ALTER TABLE `symptoms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pet_id` (`pet_id`);

--
-- Indexes for table `veterinarian`
--
ALTER TABLE `veterinarian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `veterinaryclinics`
--
ALTER TABLE `veterinaryclinics`
  ADD PRIMARY KEY (`clinic_id`);

--
-- Indexes for table `vet_petowner_association`
--
ALTER TABLE `vet_petowner_association`
  ADD PRIMARY KEY (`association_id`),
  ADD UNIQUE KEY `unique_association` (`vet_id`,`petowner_id`),
  ADD KEY `petowner_id` (`petowner_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ai_assistant`
--
ALTER TABLE `ai_assistant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ai_diagnoses`
--
ALTER TABLE `ai_diagnoses`
  MODIFY `diagnosis_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `consultation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=434344;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `petowners`
--
ALTER TABLE `petowners`
  MODIFY `owner_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pets`
--
ALTER TABLE `pets`
  MODIFY `pet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `symptoms`
--
ALTER TABLE `symptoms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;

--
-- AUTO_INCREMENT for table `veterinarian`
--
ALTER TABLE `veterinarian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `veterinaryclinics`
--
ALTER TABLE `veterinaryclinics`
  MODIFY `clinic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `vet_petowner_association`
--
ALTER TABLE `vet_petowner_association`
  MODIFY `association_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ai_diagnoses`
--
ALTER TABLE `ai_diagnoses`
  ADD CONSTRAINT `ai_diagnoses_ibfk_1` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`pet_id`) ON DELETE CASCADE;

--
-- Constraints for table `symptoms`
--
ALTER TABLE `symptoms`
  ADD CONSTRAINT `symptoms_ibfk_1` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`pet_id`) ON DELETE CASCADE;

--
-- Constraints for table `vet_petowner_association`
--
ALTER TABLE `vet_petowner_association`
  ADD CONSTRAINT `vet_petowner_association_ibfk_1` FOREIGN KEY (`vet_id`) REFERENCES `veterinarian` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vet_petowner_association_ibfk_2` FOREIGN KEY (`petowner_id`) REFERENCES `petowners` (`owner_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

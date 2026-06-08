USE licensure_predictor;

-- 1. Insert default user accounts
INSERT INTO users (email, password, account_type) VALUES
('registrar@abyanto.freedev.app', '$2y$10$3eWeLSCbPGAhvkxAwWsecOFcABYdHdTZWZATNrV2vF6v2Uti8eF8m', 'registrar'),
('program_chair@abyanto.freedev.app', '$2y$10$3eWeLSCbPGAhvkxAwWsecOFcABYdHdTZWZATNrV2vF6v2Uti8eF8m', 'program chair');

-- 2. Insert new major courses
INSERT INTO courses (id, code, name, is_major, sort_order) VALUES
-- Bachelor of Science in Civil Engineering (72-81)
(72, 'CE 101', 'CE 101 - Engineering Mechanics', 1, 72),
(73, 'CE 102', 'CE 102 - Structural Theory', 1, 73),
(74, 'CE 201', 'CE 201 - Steel Design', 1, 74),
(75, 'CE 202', 'CE 202 - Reinforced Concrete Design', 1, 75),
(76, 'CE 301', 'CE 301 - Geotechnical Engineering', 1, 76),
(77, 'CE 302', 'CE 302 - Transportation Engineering', 1, 77),
(78, 'CE 401', 'CE 401 - Hydraulics and Water Resources Engineering', 1, 78),
(79, 'CE 402', 'CE 402 - Construction Engineering and Management', 1, 79),
(80, 'ACE 1', 'ACE 1 - Advanced Structural Analysis', 1, 80),
(81, 'ACE 2', 'ACE 2 - Advanced Geotechnical Engineering', 1, 81),
-- Bachelor of Science in Electrical Engineering (82-91)
(82, 'EE 101', 'EE 101 - Electrical Circuits I', 1, 82),
(83, 'EE 102', 'EE 102 - Electrical Circuits II', 1, 83),
(84, 'EE 201', 'EE 201 - Power System Analysis', 1, 84),
(85, 'EE 202', 'EE 202 - Electrical Machines', 1, 85),
(86, 'EE 301', 'EE 301 - Electronics Engineering', 1, 86),
(87, 'EE 302', 'EE 302 - Control Systems', 1, 87),
(88, 'EE 401', 'EE 401 - Power Electronics', 1, 88),
(89, 'EE 402', 'EE 402 - Electrical Systems Design', 1, 89),
(90, 'AEE 1', 'AEE 1 - Advanced Power Systems', 1, 90),
(91, 'AEE 2', 'AEE 2 - Advanced Control Systems', 1, 91),
-- Bachelor of Secondary Education major in Filipino (92-101)
(92, 'FIL 101', 'FIL 101 - Introduksyon sa Panitikan', 1, 92),
(93, 'FIL 102', 'FIL 102 - Malikhaing Pagsulat', 1, 93),
(94, 'FIL 201', 'FIL 201 - Retorika', 1, 94),
(95, 'FIL 202', 'FIL 202 - Panitikang Pilipino', 1, 95),
(96, 'FIL 301', 'FIL 301 - Linggwistika', 1, 96),
(97, 'FIL 302', 'FIL 302 - Panitikang Rehiyonal', 1, 97),
(98, 'FIL 401', 'FIL 401 - Pagtataya sa Wika at Panitikan', 1, 98),
(99, 'FIL 402', 'FIL 402 - Pagtuturo ng Filipino', 1, 99),
(100, 'AFIL 1', 'AFIL 1 - Advanced Filipino Language Studies', 1, 100),
(101, 'AFIL 2', 'AFIL 2 - Advanced Filipino Literary Criticism', 1, 101),
-- Bachelor of Secondary Education major in English (102-111)
(102, 'ENG 101', 'ENG 101 - Introduction to Literature', 1, 102),
(103, 'ENG 102', 'ENG 102 - English Phonology and Morphology', 1, 103),
(104, 'ENG 201', 'ENG 201 - English Syntax', 1, 104),
(105, 'ENG 202', 'ENG 202 - Stylistics and Discourse Analysis', 1, 105),
(106, 'ENG 301', 'ENG 301 - Language Assessment and Evaluation', 1, 106),
(107, 'ENG 302', 'ENG 302 - Teaching Listening and Speaking', 1, 107),
(108, 'ENG 401', 'ENG 401 - Teaching Reading and Writing', 1, 108),
(109, 'ENG 402', 'ENG 402 - Language Research', 1, 109),
(110, 'AENG 1', 'AENG 1 - Advanced English Linguistics', 1, 110),
(111, 'AENG 2', 'AENG 2 - Advanced Literary Criticism', 1, 111),
-- Bachelor of Secondary Education major in Mathematics (112-121)
(112, 'MATH 101', 'MATH 101 - College Algebra', 1, 112),
(113, 'MATH 102', 'MATH 102 - Plane Trigonometry', 1, 113),
(114, 'MATH 201', 'MATH 201 - Calculus I', 1, 114),
(115, 'MATH 202', 'MATH 202 - Calculus II', 1, 115),
(116, 'MATH 301', 'MATH 301 - Linear Algebra', 1, 116),
(117, 'MATH 302', 'MATH 302 - Probability and Statistics', 1, 117),
(118, 'MATH 401', 'MATH 401 - Abstract Algebra', 1, 118),
(119, 'MATH 402', 'MATH 402 - Geometry', 1, 119),
(120, 'AMATH 1', 'AMATH 1 - Advanced Mathematics I', 1, 120),
(121, 'AMATH 2', 'AMATH 2 - Advanced Mathematics II', 1, 121),
-- Bachelor of Secondary Education major in Science (122-131)
(122, 'SCI 101', 'SCI 101 - General Biology', 1, 122),
(123, 'SCI 102', 'SCI 102 - General Chemistry', 1, 123),
(124, 'SCI 201', 'SCI 201 - General Physics', 1, 124),
(125, 'SCI 202', 'SCI 202 - Earth Science', 1, 125),
(126, 'SCI 301', 'SCI 301 - Ecology', 1, 126),
(127, 'SCI 302', 'SCI 302 - Genetics', 1, 127),
(128, 'SCI 401', 'SCI 401 - Analytical Chemistry', 1, 128),
(129, 'SCI 402', 'SCI 402 - Science Teaching Strategies', 1, 129),
(130, 'ASCI 1', 'ASCI 1 - Advanced Science I', 1, 130),
(131, 'ASCI 2', 'ASCI 2 - Advanced Science II', 1, 131),
-- Bachelor of Physical Education (132-141)
(132, 'PE 101', 'PE 101 - Foundations of Physical Education', 1, 132),
(133, 'PE 102', 'PE 102 - Anatomy and Physiology', 1, 133),
(134, 'PE 201', 'PE 201 - Sports Science', 1, 134),
(135, 'PE 202', 'PE 202 - Physical Fitness and Wellness', 1, 135),
(136, 'PE 301', 'PE 301 - Motor Learning and Development', 1, 136),
(137, 'PE 302', 'PE 302 - Dance Education', 1, 137),
(138, 'PE 401', 'PE 401 - Sports Management', 1, 138),
(139, 'PE 402', 'PE 402 - Adapted Physical Education', 1, 139),
(140, 'APE 1', 'APE 1 - Advanced Physical Education I', 1, 140),
(141, 'APE 2', 'APE 2 - Advanced Physical Education II', 1, 141),
-- Bachelor of Early Childhood Education (142-151)
(142, 'ECE 101', 'ECE 101 - Child Development', 1, 142),
(143, 'ECE 102', 'ECE 102 - Early Childhood Curriculum', 1, 143),
(144, 'ECE 201', 'ECE 201 - Child Psychology', 1, 144),
(145, 'ECE 202', 'ECE 202 - Language Development', 1, 145),
(146, 'ECE 301', 'ECE 301 - Creative Arts and Play', 1, 146),
(147, 'ECE 302', 'ECE 302 - Assessment in Early Childhood Education', 1, 147),
(148, 'ECE 401', 'ECE 401 - Inclusive Education', 1, 148),
(149, 'ECE 402', 'ECE 402 - ECE Administration', 1, 149),
(150, 'AECE 1', 'AECE 1 - Advanced Early Childhood Education I', 1, 150),
(151, 'AECE 2', 'AECE 2 - Advanced Early Childhood Education II', 1, 151),
-- Bachelor of Elementary Education (152-161)
(152, 'ELEM 101', 'ELEM 101 - Teaching Social Studies', 1, 152),
(153, 'ELEM 102', 'ELEM 102 - Teaching Science in Elementary Grades', 1, 153),
(154, 'ELEM 201', 'ELEM 201 - Teaching Mathematics', 1, 154),
(155, 'ELEM 202', 'ELEM 202 - Teaching English', 1, 155),
(156, 'ELEM 301', 'ELEM 301 - Teaching Filipino', 1, 156),
(157, 'ELEM 302', 'ELEM 302 - Educational Technology', 1, 157),
(158, 'ELEM 401', 'ELEM 401 - Classroom Management', 1, 158),
(159, 'ELEM 402', 'ELEM 402 - Curriculum Development', 1, 159),
(160, 'AELEM 1', 'AELEM 1 - Advanced Elementary Education I', 1, 160),
(161, 'AELEM 2', 'AELEM 2 - Advanced Elementary Education II', 1, 161);

-- 3. Insert new students
INSERT INTO students (id, student_id, full_name, gwa, licensure_result, major_average, program, city_municipality, province, postal_code, age, sex, secondary_school_name, secondary_school_type, father_educational_attainment, mother_educational_attainment, monthly_family_income) VALUES
-- Bachelor of Science in Civil Engineering (27-31)
(27, '301', 'Juan dela Cruz', 87.5, 'PASS', 1.75, 'Bachelor of Science in Civil Engineering', 'Bislig City', 'Surigao del Sur', '8311', 22, 'Male', 'Bislig City National High School', 'Public', 'College Graduate', 'College Graduate', '30,001 to 40,000'),
(28, '302', 'Maria Reyes', 84.2, 'FAIL', 3.22, 'Bachelor of Science in Civil Engineering', 'Tagbina', 'Surigao del Sur', '8308', 23, 'Female', 'Mangagoy National High School', 'Public', 'High School Graduate', 'College Graduate', '20,001 to 30,000'),
(29, '303', 'Carlos Mendoza', 90.1, 'PASS', 1.48, 'Bachelor of Science in Civil Engineering', 'Barobo', 'Surigao del Sur', '8309', 21, 'Male', 'De La Salle John Bosco College High School', 'Private', 'College Graduate', 'Post-Graduate', '50,001 to 100,000 and above'),
(30, '304', 'Anna Santos', 79.8, 'FAIL', 3.45, 'Bachelor of Science in Civil Engineering', 'Lianga', 'Surigao del Sur', '8307', 22, 'Female', 'Colegio de San Nicolas de Tolentino', 'Private', 'College Undergraduate', 'College Graduate', '15,001 to 20,000'),
(31, '305', 'Pedro Gonzales', 86.3, 'PASS', 1.63, 'Bachelor of Science in Civil Engineering', 'Hinatuan', 'Surigao del Sur', '8310', 24, 'Male', 'Tabon M. Estrella National High School', 'Public', 'High School Graduate', 'Elementary Graduate', '10,000 to 15,000'),
-- Bachelor of Science in Electrical Engineering (32-36)
(32, '306', 'Sofia Villanueva', 91.2, 'PASS', 1.51, 'Bachelor of Science in Electrical Engineering', 'Tandag City', 'Surigao del Sur', '8300', 22, 'Female', 'Andres Soriano Colleges of Bislig High School', 'Private', 'Post-Graduate', 'College Graduate', '40,001 to 50,000'),
(33, '307', 'Diego Ramos', 83.5, 'FAIL', 3.18, 'Bachelor of Science in Electrical Engineering', 'Barobo', 'Surigao del Sur', '8309', 23, 'Male', 'De La Salle John Bosco College High School', 'Private', 'College Graduate', 'High School Graduate', '30,001 to 40,000'),
(34, '308', 'Isabella Torres', 88.7, 'PASS', 1.82, 'Bachelor of Science in Electrical Engineering', 'Bislig City', 'Surigao del Sur', '8311', 21, 'Female', 'Bislig City National High School', 'Public', 'College Graduate', 'Post-Graduate', '50,001 to 100,000 and above'),
(35, '309', 'Luis Garcia', 77.4, 'FAIL', 3.67, 'Bachelor of Science in Electrical Engineering', 'Tagbina', 'Surigao del Sur', '8308', 24, 'Male', 'Mangagoy National High School', 'Public', 'Elementary Graduate', 'College Undergraduate', 'Below 10,000'),
(36, '310', 'Camille Perez', 85.9, 'PASS', 1.58, 'Bachelor of Science in Electrical Engineering', 'Lianga', 'Surigao del Sur', '8307', 20, 'Female', 'Colegio de San Nicolas de Tolentino', 'Private', 'College Undergraduate', 'College Graduate', '20,001 to 30,000'),
-- Bachelor of Secondary Education major in Filipino (37-41)
(37, '311', 'Andres Bautista', 86.4, 'PASS', 1.69, 'Bachelor of Secondary Education major in Filipino', 'Hinatuan', 'Surigao del Sur', '8310', 23, 'Male', 'Tabon M. Estrella National High School', 'Public', 'High School Graduate', 'Elementary Graduate', '15,001 to 20,000'),
(38, '312', 'Rosa Martinez', 82.1, 'FAIL', 3.31, 'Bachelor of Secondary Education major in Filipino', 'Bislig City', 'Surigao del Sur', '8311', 22, 'Female', 'Bislig City National High School', 'Public', 'College Graduate', 'College Graduate', '30,001 to 40,000'),
(39, '313', 'Jose Rizal', 89.5, 'PASS', 1.42, 'Bachelor of Secondary Education major in Filipino', 'Tandag City', 'Surigao del Sur', '8300', 24, 'Male', 'Andres Soriano Colleges of Bislig High School', 'Private', 'Post-Graduate', 'College Graduate', '40,001 to 50,000'),
(40, '314', 'Liza Flores', 80.3, 'FAIL', 3.52, 'Bachelor of Secondary Education major in Filipino', 'Barobo', 'Surigao del Sur', '8309', 21, 'Female', 'De La Salle John Bosco College High School', 'Private', 'College Undergraduate', 'High School Graduate', '10,000 to 15,000'),
(41, '315', 'Emilio Aguilar', 84.7, 'PASS', 1.71, 'Bachelor of Secondary Education major in Filipino', 'Tagbina', 'Surigao del Sur', '8308', 23, 'Male', 'Mangagoy National High School', 'Public', 'Elementary Graduate', 'College Undergraduate', '20,001 to 30,000'),
-- Bachelor of Secondary Education major in English (42-46)
(42, '316', 'Grace Lim', 90.8, 'PASS', 1.39, 'Bachelor of Secondary Education major in English', 'Lianga', 'Surigao del Sur', '8307', 22, 'Female', 'Colegio de San Nicolas de Tolentino', 'Private', 'College Graduate', 'Post-Graduate', '50,001 to 100,000 and above'),
(43, '317', 'Mark Hernandez', 81.6, 'FAIL', 3.28, 'Bachelor of Secondary Education major in English', 'Hinatuan', 'Surigao del Sur', '8310', 24, 'Male', 'Tabon M. Estrella National High School', 'Public', 'High School Graduate', 'College Graduate', 'Below 10,000'),
(44, '318', 'Angel Cruz', 87.3, 'PASS', 1.55, 'Bachelor of Secondary Education major in English', 'Bislig City', 'Surigao del Sur', '8311', 21, 'Female', 'Bislig City National High School', 'Public', 'College Graduate', 'College Graduate', '30,001 to 40,000'),
(45, '319', 'Kevin Santos', 78.9, 'FAIL', 3.74, 'Bachelor of Secondary Education major in English', 'Tandag City', 'Surigao del Sur', '8300', 23, 'Male', 'Andres Soriano Colleges of Bislig High School', 'Private', 'College Undergraduate', 'High School Graduate', '15,001 to 20,000'),
(46, '320', 'Diana Reyes', 85.1, 'PASS', 1.67, 'Bachelor of Secondary Education major in English', 'Barobo', 'Surigao del Sur', '8309', 22, 'Female', 'De La Salle John Bosco College High School', 'Private', 'Post-Graduate', 'College Graduate', '40,001 to 50,000'),
-- Bachelor of Secondary Education major in Mathematics (47-51)
(47, '321', 'Ramon Gutierrez', 92.4, 'PASS', 1.33, 'Bachelor of Secondary Education major in Mathematics', 'Tagbina', 'Surigao del Sur', '8308', 21, 'Male', 'Mangagoy National High School', 'Public', 'College Graduate', 'Post-Graduate', '50,001 to 100,000 and above'),
(48, '322', 'Nina Salvador', 83.8, 'FAIL', 3.41, 'Bachelor of Secondary Education major in Mathematics', 'Lianga', 'Surigao del Sur', '8307', 23, 'Female', 'Colegio de San Nicolas de Tolentino', 'Private', 'High School Graduate', 'College Undergraduate', '20,001 to 30,000'),
(49, '323', 'Leonardo Cruz', 88.2, 'PASS', 1.59, 'Bachelor of Secondary Education major in Mathematics', 'Hinatuan', 'Surigao del Sur', '8310', 22, 'Male', 'Tabon M. Estrella National High School', 'Public', 'College Undergraduate', 'College Graduate', '30,001 to 40,000'),
(50, '324', 'Patricia Villar', 79.5, 'FAIL', 3.39, 'Bachelor of Secondary Education major in Mathematics', 'Bislig City', 'Surigao del Sur', '8311', 24, 'Female', 'Bislig City National High School', 'Public', 'Elementary Graduate', 'High School Graduate', 'Below 10,000'),
(51, '325', 'Antonio Mercado', 86.7, 'PASS', 1.48, 'Bachelor of Secondary Education major in Mathematics', 'Tandag City', 'Surigao del Sur', '8300', 20, 'Male', 'Andres Soriano Colleges of Bislig High School', 'Private', 'Post-Graduate', 'College Graduate', '40,001 to 50,000'),
-- Bachelor of Secondary Education major in Science (52-56)
(52, '326', 'Martha Gomez', 89.1, 'PASS', 1.54, 'Bachelor of Secondary Education major in Science', 'Barobo', 'Surigao del Sur', '8309', 22, 'Female', 'De La Salle John Bosco College High School', 'Private', 'College Graduate', 'College Graduate', '30,001 to 40,000'),
(53, '327', 'Rodel Castro', 82.4, 'FAIL', 3.25, 'Bachelor of Secondary Education major in Science', 'Tagbina', 'Surigao del Sur', '8308', 23, 'Male', 'Mangagoy National High School', 'Public', 'High School Graduate', 'Elementary Graduate', '15,001 to 20,000'),
(54, '328', 'Clara Agustin', 87.9, 'PASS', 1.62, 'Bachelor of Secondary Education major in Science', 'Lianga', 'Surigao del Sur', '8307', 21, 'Female', 'Colegio de San Nicolas de Tolentino', 'Private', 'College Undergraduate', 'Post-Graduate', '40,001 to 50,000'),
(55, '329', 'Francisco Javier', 76.8, 'FAIL', 3.88, 'Bachelor of Secondary Education major in Science', 'Hinatuan', 'Surigao del Sur', '8310', 25, 'Male', 'Tabon M. Estrella National High School', 'Public', 'Elementary Graduate', 'College Undergraduate', 'Below 10,000'),
(56, '330', 'Sandra Ramos', 84.5, 'PASS', 1.73, 'Bachelor of Secondary Education major in Science', 'Bislig City', 'Surigao del Sur', '8311', 22, 'Female', 'Bislig City National High School', 'Public', 'College Graduate', 'High School Graduate', '20,001 to 30,000'),
-- Bachelor of Physical Education (57-61)
(57, '331', 'Jonathan Salazar', 85.6, 'PASS', 1.78, 'Bachelor of Physical Education', 'Tandag City', 'Surigao del Sur', '8300', 23, 'Male', 'Andres Soriano Colleges of Bislig High School', 'Private', 'College Graduate', 'College Graduate', '30,001 to 40,000'),
(58, '332', 'Michelle Delos Santos', 80.9, 'FAIL', 3.15, 'Bachelor of Physical Education', 'Barobo', 'Surigao del Sur', '8309', 24, 'Female', 'De La Salle John Bosco College High School', 'Private', 'High School Graduate', 'Post-Graduate', '10,000 to 15,000'),
(59, '333', 'Bernard Torres', 88.3, 'PASS', 1.41, 'Bachelor of Physical Education', 'Tagbina', 'Surigao del Sur', '8308', 21, 'Male', 'Mangagoy National High School', 'Public', 'College Undergraduate', 'College Graduate', '40,001 to 50,000'),
(60, '334', 'Lorna Alegre', 81.2, 'FAIL', 3.36, 'Bachelor of Physical Education', 'Lianga', 'Surigao del Sur', '8307', 22, 'Female', 'Colegio de San Nicolas de Tolentino', 'Private', 'Elementary Graduate', 'High School Graduate', '15,001 to 20,000'),
(61, '335', 'Ricky Belen', 87.1, 'PASS', 1.59, 'Bachelor of Physical Education', 'Hinatuan', 'Surigao del Sur', '8310', 20, 'Male', 'Tabon M. Estrella National High School', 'Public', 'Post-Graduate', 'College Graduate', '50,001 to 100,000 and above'),
-- Bachelor of Early Childhood Education (62-66)
(62, '336', 'Teresa Valencia', 90.3, 'PASS', 1.38, 'Bachelor of Early Childhood Education', 'Bislig City', 'Surigao del Sur', '8311', 22, 'Female', 'Bislig City National High School', 'Public', 'College Graduate', 'Post-Graduate', '40,001 to 50,000'),
(63, '337', 'Gregorio Mendoza', 83.1, 'FAIL', 3.42, 'Bachelor of Early Childhood Education', 'Tandag City', 'Surigao del Sur', '8300', 23, 'Male', 'Andres Soriano Colleges of Bislig High School', 'Private', 'High School Graduate', 'College Graduate', '20,001 to 30,000'),
(64, '338', 'Fely Marquez', 86.5, 'PASS', 1.64, 'Bachelor of Early Childhood Education', 'Barobo', 'Surigao del Sur', '8309', 24, 'Female', 'De La Salle John Bosco College High School', 'Private', 'College Undergraduate', 'College Graduate', '30,001 to 40,000'),
(65, '339', 'Roland Castro', 78.4, 'FAIL', 3.58, 'Bachelor of Early Childhood Education', 'Tagbina', 'Surigao del Sur', '8308', 25, 'Male', 'Mangagoy National High School', 'Public', 'Elementary Graduate', 'Elementary Graduate', 'Below 10,000'),
(66, '340', 'Vivian Tan', 85.8, 'PASS', 1.61, 'Bachelor of Early Childhood Education', 'Lianga', 'Surigao del Sur', '8307', 21, 'Female', 'Colegio de San Nicolas de Tolentino', 'Private', 'Post-Graduate', 'College Graduate', '50,001 to 100,000 and above'),
-- Bachelor of Elementary Education (67-71)
(67, '341', 'Edgar Robles', 88.6, 'PASS', 1.45, 'Bachelor of Elementary Education', 'Hinatuan', 'Surigao del Sur', '8310', 22, 'Male', 'Tabon M. Estrella National High School', 'Public', 'College Graduate', 'College Graduate', '30,001 to 40,000'),
(68, '342', 'Cynthia Lopez', 82.7, 'FAIL', 3.29, 'Bachelor of Elementary Education', 'Bislig City', 'Surigao del Sur', '8311', 23, 'Female', 'Bislig City National High School', 'Public', 'High School Graduate', 'College Undergraduate', '15,001 to 20,000'),
(69, '343', 'Ricardo Santiago', 89.4, 'PASS', 1.52, 'Bachelor of Elementary Education', 'Tandag City', 'Surigao del Sur', '8300', 21, 'Male', 'Andres Soriano Colleges of Bislig High School', 'Private', 'College Undergraduate', 'Post-Graduate', '40,001 to 50,000'),
(70, '344', 'Gloria Pascual', 80.1, 'FAIL', 3.63, 'Bachelor of Elementary Education', 'Barobo', 'Surigao del Sur', '8309', 24, 'Female', 'De La Salle John Bosco College High School', 'Private', 'Elementary Graduate', 'College Graduate', '10,000 to 15,000'),
(71, '345', 'Benjamin Navarro', 86.9, 'PASS', 1.56, 'Bachelor of Elementary Education', 'Tagbina', 'Surigao del Sur', '8308', 22, 'Male', 'Mangagoy National High School', 'Public', 'Post-Graduate', 'College Graduate', '20,001 to 30,000');

-- 4. Insert new student grades
INSERT INTO student_grades (student_id, course_id, grade) VALUES
-- Student 27 (CE) - major courses 72-81, GE 4,6,7,9,11,12
(27, 72, 1.33), (27, 73, 1.98), (27, 74, 1.75), (27, 75, 2.08), (27, 76, 1.61), (27, 77, 2.13), (27, 78, 1.52), (27, 79, 2.28), (27, 80, 1.29), (27, 81, 1.74),
(27, 57, 2.31), (27, 59, 1.51), (27, 60, 2.35), (27, 62, 2.38), (27, 64, 1.01), (27, 65, 1.97),
-- Student 28 (CE)
(28, 72, 2.74), (28, 73, 2.88), (28, 74, 2.83), (28, 75, 2.83), (28, 76, 5), (28, 77, 2.54), (28, 78, 2.53), (28, 79, 2.79), (28, 80, 2.49), (28, 81, 5),
(28, 57, 2.3), (28, 59, 2.16), (28, 62, 2.36), (28, 63, 1.85), (28, 64, 2.31), (28, 65, 1.12),
-- Student 29 (CE)
(29, 72, 1.53), (29, 73, 1.89), (29, 74, 2.15), (29, 75, 1), (29, 76, 1.55), (29, 77, 2.05), (29, 78, 1.53), (29, 79, 1.97), (29, 80, 1.6), (29, 81, 1.54),
(29, 57, 1.22), (29, 58, 2.68), (29, 59, 1.08), (29, 60, 2.6), (29, 61, 1.67), (29, 65, 2.52),
-- Student 30 (CE)
(30, 72, 2.43), (30, 73, 2.92), (30, 74, 2.47), (30, 75, 3), (30, 76, 2.88), (30, 77, 2.33), (30, 78, 2.91), (30, 79, 5), (30, 80, 2.73), (30, 81, 2.49),
(30, 57, 2.79), (30, 58, 2.4), (30, 59, 1.26), (30, 60, 1.67), (30, 63, 1.47), (30, 64, 1.93),
-- Student 31 (CE)
(31, 72, 1.88), (31, 73, 2.22), (31, 74, 1.05), (31, 75, 2.1), (31, 76, 1.79), (31, 77, 1.79), (31, 78, 1.82), (31, 79, 1.54), (31, 80, 1.17), (31, 81, 2.23),
(31, 58, 1.42), (31, 60, 1.17), (31, 61, 2.02), (31, 62, 1.49), (31, 64, 2.14), (31, 65, 1.28),
-- Student 32 (EE) - major courses 82-91, GE 4,5,7,8,10,12
(32, 82, 1.42), (32, 83, 1.23), (32, 84, 1.56), (32, 85, 2.01), (32, 86, 1.38), (32, 87, 1.89), (32, 88, 1.67), (32, 89, 1.44), (32, 90, 1.12), (32, 91, 1.34),
(32, 57, 2.58), (32, 58, 2.22), (32, 60, 2.64), (32, 61, 1.81), (32, 63, 1.94), (32, 65, 2.28),
-- Student 33 (EE)
(33, 82, 2.88), (33, 83, 5), (33, 84, 2.76), (33, 85, 2.5), (33, 86, 5), (33, 87, 2.7), (33, 88, 3), (33, 89, 5), (33, 90, 2.76), (33, 91, 2.36),
(33, 57, 1.39), (33, 59, 2.27), (33, 60, 1.96), (33, 61, 1.04), (33, 62, 2.73), (33, 65, 2.66),
-- Student 34 (EE)
(34, 82, 1.33), (34, 83, 1.96), (34, 84, 1.54), (34, 85, 1), (34, 86, 2), (34, 87, 1.09), (34, 88, 1.96), (34, 89, 1.87), (34, 90, 1), (34, 91, 1.6),
(34, 57, 2.71), (34, 58, 1.75), (34, 60, 1.79), (34, 61, 2.8), (34, 62, 2.78), (34, 63, 2.27),
-- Student 35 (EE)
(35, 82, 2.38), (35, 83, 5), (35, 84, 2.49), (35, 85, 2.48), (35, 86, 2.41), (35, 87, 2.62), (35, 88, 2.82), (35, 89, 2.46), (35, 90, 5), (35, 91, 5),
(35, 57, 1.3), (35, 60, 1.29), (35, 62, 2.84), (35, 63, 2.88), (35, 64, 2.58), (35, 65, 1.08),
-- Student 36 (EE)
(36, 82, 1.49), (36, 83, 2.23), (36, 84, 1.59), (36, 85, 1.49), (36, 86, 2), (36, 87, 1.4), (36, 88, 2.14), (36, 89, 2.27), (36, 90, 1.52), (36, 91, 1.77),
(36, 58, 1.86), (36, 59, 2.62), (36, 61, 2.17), (36, 62, 1.08), (36, 64, 1.68), (36, 65, 2.63),
-- Student 37 (FIL) - major courses 92-101, GE 4,5,6,8,10,11
(37, 92, 1.67), (37, 93, 1.48), (37, 94, 1.18), (37, 95, 2), (37, 96, 1.06), (37, 97, 1.5), (37, 98, 1.7), (37, 99, 1.18), (37, 100, 1.25), (37, 101, 1.07),
(37, 57, 1.29), (37, 58, 1.19), (37, 59, 1.36), (37, 61, 2.53), (37, 63, 1.09), (37, 64, 1.68),
-- Student 38 (FIL)
(38, 92, 2.78), (38, 93, 2.32), (38, 94, 5), (38, 95, 5), (38, 96, 5), (38, 97, 2.95), (38, 98, 2.97), (38, 99, 2.93), (38, 100, 5), (38, 101, 2.75),
(38, 57, 1.8), (38, 58, 1.25), (38, 59, 2.51), (38, 61, 1.02), (38, 63, 2.39), (38, 65, 2.49),
-- Student 39 (FIL)
(39, 92, 1.33), (39, 93, 1.82), (39, 94, 1.15), (39, 95, 1.65), (39, 96, 1.76), (39, 97, 1.62), (39, 98, 1.42), (39, 99, 1.11), (39, 100, 1.47), (39, 101, 1.95),
(39, 57, 2.57), (39, 58, 1.76), (39, 59, 1.06), (39, 60, 1.32), (39, 62, 1.59), (39, 65, 1.43),
-- Student 40 (FIL)
(40, 92, 5), (40, 93, 2.66), (40, 94, 2.68), (40, 95, 2.42), (40, 96, 5), (40, 97, 2.37), (40, 98, 5), (40, 99, 2.58), (40, 100, 2.72), (40, 101, 5),
(40, 57, 2.81), (40, 58, 1.12), (40, 59, 2.53), (40, 62, 2.33), (40, 63, 2.42), (40, 64, 3),
-- Student 41 (FIL)
(41, 92, 1.42), (41, 93, 1.8), (41, 94, 1.99), (41, 95, 2), (41, 96, 1.77), (41, 97, 1.01), (41, 98, 1.31), (41, 99, 2.09), (41, 100, 1.95), (41, 101, 1.81),
(41, 58, 2.22), (41, 59, 2.18), (41, 60, 2.92), (41, 62, 2.47), (41, 64, 2.15), (41, 65, 2.51),
-- Student 42 (ENG) - major courses 102-111, GE 4,6,7,9,10,12
(42, 102, 1.34), (42, 103, 2.07), (42, 104, 2.21), (42, 105, 1.33), (42, 106, 1.88), (42, 107, 1.72), (42, 108, 1.78), (42, 109, 1.81), (42, 110, 1.35), (42, 111, 1.02),
(42, 57, 1.45), (42, 59, 1.77), (42, 60, 1.3), (42, 62, 2.76), (42, 63, 2.11), (42, 65, 1.18),
-- Student 43 (ENG)
(43, 102, 2.6), (43, 103, 2.74), (43, 104, 2.39), (43, 105, 5), (43, 106, 2.55), (43, 107, 2.88), (43, 108, 2.63), (43, 109, 2.66), (43, 110, 5), (43, 111, 2.87),
(43, 58, 2.74), (43, 59, 1.35), (43, 61, 2.38), (43, 62, 1.86), (43, 64, 1.9), (43, 65, 1.02),
-- Student 44 (ENG)
(44, 102, 1.27), (44, 103, 1.42), (44, 104, 1.19), (44, 105, 1.32), (44, 106, 1.66), (44, 107, 1.18), (44, 108, 2.13), (44, 109, 1.26), (44, 110, 1.48), (44, 111, 1.46),
(44, 61, 2.05), (44, 62, 2.8), (44, 63, 1.62), (44, 64, 1.04), (44, 65, 1.52), (44, 57, 1.7),
-- Student 45 (ENG)
(45, 102, 2.69), (45, 103, 2.58), (45, 104, 2.94), (45, 105, 2.98), (45, 106, 5), (45, 107, 5), (45, 108, 2.39), (45, 109, 2.68), (45, 110, 2.39), (45, 111, 2.62),
(45, 57, 1.16), (45, 58, 1.65), (45, 60, 2.65), (45, 63, 2.36), (45, 64, 2.7), (45, 65, 1.98),
-- Student 46 (ENG)
(46, 102, 2.3), (46, 103, 1.84), (46, 104, 1.23), (46, 105, 1.51), (46, 106, 1.56), (46, 107, 2), (46, 108, 2.22), (46, 109, 1.46), (46, 110, 1.55), (46, 111, 2.3),
(46, 57, 1.83), (46, 58, 2.1), (46, 59, 2.09), (46, 60, 2.14), (46, 62, 1.19), (46, 63, 2.48),
-- Student 47 (MATH) - major courses 112-121, GE 4,5,7,9,11,12
(47, 112, 1.25), (47, 113, 1.43), (47, 114, 1.04), (47, 115, 1.42), (47, 116, 1.75), (47, 117, 1.73), (47, 118, 1.02), (47, 119, 1.24), (47, 120, 2.04), (47, 121, 1.92),
(47, 57, 2.59), (47, 58, 1.48), (47, 60, 1.83), (47, 62, 2.91), (47, 64, 2.24), (47, 65, 2.23),
-- Student 48 (MATH)
(48, 112, 2.98), (48, 113, 2.82), (48, 114, 2.89), (48, 115, 2.73), (48, 116, 2.43), (48, 117, 2.67), (48, 118, 5), (48, 119, 2.71), (48, 120, 2.92), (48, 121, 5),
(48, 57, 2.05), (48, 58, 2.3), (48, 60, 1.85), (48, 61, 2.82), (48, 63, 2.69), (48, 65, 1.43),
-- Student 49 (MATH)
(49, 112, 1.4), (49, 113, 1.16), (49, 114, 1.84), (49, 115, 1.47), (49, 116, 1.67), (49, 117, 1.72), (49, 118, 1.43), (49, 119, 1.2), (49, 120, 1.67), (49, 121, 1.94),
(49, 59, 1.33), (49, 60, 1.16), (49, 62, 2.98), (49, 63, 2.55), (49, 64, 2), (49, 65, 1.2),
-- Student 50 (MATH)
(50, 112, 5), (50, 113, 2.91), (50, 114, 2.6), (50, 115, 2.67), (50, 116, 2.94), (50, 117, 2.72), (50, 118, 5), (50, 119, 2.31), (50, 120, 2.49), (50, 121, 2.68),
(50, 57, 1.69), (50, 58, 1.48), (50, 59, 2.87), (50, 60, 1.52), (50, 62, 2.53), (50, 64, 2.79),
-- Student 51 (MATH)
(51, 112, 1.27), (51, 113, 1.33), (51, 114, 1.71), (51, 115, 1.07), (51, 116, 1.57), (51, 117, 1.58), (51, 118, 1.49), (51, 119, 1.97), (51, 120, 2.15), (51, 121, 1.37),
(51, 59, 1.5), (51, 61, 1.57), (51, 62, 2.12), (51, 63, 1.85), (51, 64, 2.18), (51, 65, 2.82),
-- Student 52 (SCI) - major courses 122-131, GE 4,5,6,7,8,10
(52, 122, 1.64), (52, 123, 1.66), (52, 124, 1.83), (52, 125, 2.23), (52, 126, 1.56), (52, 127, 1.92), (52, 128, 1.26), (52, 129, 1.43), (52, 130, 2.22), (52, 131, 1.77),
(52, 57, 1.53), (52, 58, 2.46), (52, 59, 1.53), (52, 60, 2.37), (52, 61, 2.06), (52, 63, 2.04),
-- Student 53 (SCI)
(53, 122, 2.9), (53, 123, 2.76), (53, 124, 2.54), (53, 125, 5), (53, 126, 5), (53, 127, 2.43), (53, 128, 2.74), (53, 129, 2.32), (53, 130, 2.33), (53, 131, 2.38),
(53, 57, 2.2), (53, 58, 2.59), (53, 60, 1.27), (53, 62, 1.49), (53, 64, 2.42), (53, 65, 2.8),
-- Student 54 (SCI)
(54, 122, 2.08), (54, 123, 2.29), (54, 124, 2.28), (54, 125, 1.9), (54, 126, 1.97), (54, 127, 2.01), (54, 128, 1.56), (54, 129, 1.49), (54, 130, 1.38), (54, 131, 1.41),
(54, 58, 1.43), (54, 59, 2.29), (54, 61, 2.54), (54, 62, 2.12), (54, 64, 2.44), (54, 65, 1.52),
-- Student 55 (SCI)
(55, 122, 2.94), (55, 123, 2.95), (55, 124, 2.81), (55, 125, 5), (55, 126, 5), (55, 127, 5), (55, 128, 2.42), (55, 129, 2.55), (55, 130, 2.76), (55, 131, 2.37),
(55, 57, 2.68), (55, 60, 1.48), (55, 61, 1.69), (55, 62, 1.66), (55, 63, 2.61), (55, 65, 1.82),
-- Student 56 (SCI)
(56, 122, 1.51), (56, 123, 1.28), (56, 124, 1.69), (56, 125, 1.95), (56, 126, 1.47), (56, 127, 1.13), (56, 128, 2.1), (56, 129, 1.3), (56, 130, 1.6), (56, 131, 1.16),
(56, 57, 2.11), (56, 59, 1.53), (56, 60, 1.73), (56, 61, 1.22), (56, 64, 1.3), (56, 65, 2.33),
-- Student 57 (BPEd) - major courses 132-141, GE 4,5,7,8,11,12
(57, 132, 1.33), (57, 133, 1.98), (57, 134, 1.75), (57, 135, 2.08), (57, 136, 1.61), (57, 137, 2.13), (57, 138, 1.52), (57, 139, 2.28), (57, 140, 1.29), (57, 141, 1.74),
(57, 57, 2.31), (57, 58, 2.44), (57, 60, 2.35), (57, 61, 1.9), (57, 64, 2.01), (57, 65, 1.97),
-- Student 58 (BPEd)
(58, 132, 2.74), (58, 133, 2.88), (58, 134, 2.83), (58, 135, 2.83), (58, 136, 5), (58, 137, 2.54), (58, 138, 2.53), (58, 139, 2.79), (58, 140, 2.49), (58, 141, 5),
(58, 57, 2.3), (58, 59, 2.16), (58, 60, 1.96), (58, 62, 2.36), (58, 63, 1.85), (58, 64, 2.31),
-- Student 59 (BPEd)
(59, 132, 1.53), (59, 133, 1.89), (59, 134, 2.15), (59, 135, 1), (59, 136, 1.55), (59, 137, 2.05), (59, 138, 1.53), (59, 139, 1.97), (59, 140, 1.6), (59, 141, 1.54),
(59, 57, 1.22), (59, 58, 2.68), (59, 59, 1.08), (59, 60, 2.6), (59, 61, 1.67), (59, 65, 2.52),
-- Student 60 (BPEd)
(60, 132, 2.43), (60, 133, 2.92), (60, 134, 2.47), (60, 135, 3), (60, 136, 2.88), (60, 137, 2.33), (60, 138, 2.91), (60, 139, 5), (60, 140, 2.73), (60, 141, 2.49),
(60, 57, 2.79), (60, 58, 2.4), (60, 59, 1.26), (60, 60, 1.67), (60, 63, 1.47), (60, 65, 1.43),
-- Student 61 (BPEd)
(61, 132, 1.88), (61, 133, 2.22), (61, 134, 1.05), (61, 135, 2.1), (61, 136, 1.79), (61, 137, 1.79), (61, 138, 1.82), (61, 139, 1.54), (61, 140, 1.17), (61, 141, 2.23),
(61, 58, 1.42), (61, 60, 1.17), (61, 61, 2.02), (61, 62, 1.49), (61, 64, 2.14), (61, 65, 1.28),
-- Student 62 (BECEd) - major courses 142-151, GE 4,6,7,9,10,12
(62, 142, 1.34), (62, 143, 2.07), (62, 144, 2.21), (62, 145, 1.33), (62, 146, 1.88), (62, 147, 1.72), (62, 148, 1.78), (62, 149, 1.81), (62, 150, 1.35), (62, 151, 1.02),
(62, 57, 2.58), (62, 59, 2.27), (62, 60, 1.79), (62, 62, 2.78), (62, 63, 1.94), (62, 65, 2.28),
-- Student 63 (BECEd)
(63, 142, 2.88), (63, 143, 5), (63, 144, 2.76), (63, 145, 2.5), (63, 146, 5), (63, 147, 2.7), (63, 148, 3), (63, 149, 5), (63, 150, 2.76), (63, 151, 2.36),
(63, 57, 1.39), (63, 59, 1.58), (63, 62, 2.73), (63, 63, 2.72), (63, 64, 2.65), (63, 65, 2.66),
-- Student 64 (BECEd)
(64, 142, 1.33), (64, 143, 1.96), (64, 144, 1.54), (64, 145, 1), (64, 146, 2), (64, 147, 1.09), (64, 148, 1.96), (64, 149, 1.87), (64, 150, 1), (64, 151, 1.6),
(64, 57, 2.71), (64, 58, 1.75), (64, 60, 1.79), (64, 61, 2.8), (64, 62, 2.78), (64, 63, 2.27),
-- Student 65 (BECEd)
(65, 142, 2.38), (65, 143, 5), (65, 144, 2.49), (65, 145, 2.48), (65, 146, 2.41), (65, 147, 2.62), (65, 148, 2.82), (65, 149, 2.46), (65, 150, 5), (65, 151, 5),
(65, 57, 1.3), (65, 60, 1.29), (65, 62, 2.84), (65, 63, 2.88), (65, 64, 2.58), (65, 65, 1.08),
-- Student 66 (BECEd)
(66, 142, 1.49), (66, 143, 2.23), (66, 144, 1.59), (66, 145, 1.49), (66, 146, 2), (66, 147, 1.4), (66, 148, 2.14), (66, 149, 2.27), (66, 150, 1.52), (66, 151, 1.77),
(66, 58, 1.86), (66, 59, 2.62), (66, 61, 2.17), (66, 62, 1.08), (66, 64, 1.68), (66, 65, 2.63),
-- Student 67 (BEEd) - major courses 152-161, GE 57,58,60,61,63,64
(67, 152, 1.67), (67, 153, 1.48), (67, 154, 1.18), (67, 155, 2), (67, 156, 1.06), (67, 157, 1.5), (67, 158, 1.7), (67, 159, 1.18), (67, 160, 1.25), (67, 161, 1.07),
(67, 57, 1.29), (67, 58, 1.19), (67, 60, 1.85), (67, 61, 2.06), (67, 63, 1.09), (67, 64, 1.68),
-- Student 68 (BEEd)
(68, 152, 2.78), (68, 153, 2.32), (68, 154, 5), (68, 155, 5), (68, 156, 5), (68, 157, 2.95), (68, 158, 2.97), (68, 159, 2.93), (68, 160, 5), (68, 161, 2.75),
(68, 57, 1.8), (68, 58, 1.25), (68, 59, 2.51), (68, 60, 1.52), (68, 63, 2.39), (68, 65, 2.49),
-- Student 69 (BEEd)
(69, 152, 1.33), (69, 153, 1.82), (69, 154, 1.15), (69, 155, 1.65), (69, 156, 1.76), (69, 157, 1.62), (69, 158, 1.42), (69, 159, 1.11), (69, 160, 1.47), (69, 161, 1.95),
(69, 57, 2.57), (69, 58, 1.76), (69, 59, 1.06), (69, 60, 1.32), (69, 62, 1.59), (69, 65, 1.43),
-- Student 70 (BEEd)
(70, 152, 5), (70, 153, 2.66), (70, 154, 2.68), (70, 155, 2.42), (70, 156, 5), (70, 157, 2.37), (70, 158, 5), (70, 159, 2.58), (70, 160, 2.72), (70, 161, 5),
(70, 57, 2.81), (70, 58, 1.12), (70, 59, 2.53), (70, 62, 2.33), (70, 63, 2.42), (70, 64, 3),
-- Student 71 (BEEd)
(71, 152, 1.42), (71, 153, 1.8), (71, 154, 1.99), (71, 155, 2), (71, 156, 1.77), (71, 157, 1.01), (71, 158, 1.31), (71, 159, 2.09), (71, 160, 1.95), (71, 161, 1.81),
(71, 58, 2.22), (71, 59, 2.18), (71, 60, 2.92), (71, 62, 2.47), (71, 64, 2.15), (71, 65, 2.51);

-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 16, 2023 at 01:25 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `triprecommendertest`
--

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `userID` int(11) NOT NULL,
  `API_ID` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`userID`, `API_ID`) VALUES
(1, 'kzxpl9HidQVMEuUoRVB7nA'),
(20, '0BgrDm4tfQBtlEV-ZqtAtg'),
(20, 'kzxpl9HidQVMEuUoRVB7nA');

-- --------------------------------------------------------

--
-- Table structure for table `pois`
--

CREATE TABLE `pois` (
  `Lat` decimal(15,10) NOT NULL,
  `Lng` decimal(15,10) NOT NULL,
  `Category` text NOT NULL,
  `id` int(11) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT NULL,
  `num_ratings` int(100) DEFAULT NULL,
  `API_ID` varchar(55) DEFAULT NULL,
  `reviews` mediumtext DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `price` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pois`
--

INSERT INTO `pois` (`Lat`, `Lng`, `Category`, `id`, `address`, `phone`, `name`, `rating`, `num_ratings`, `API_ID`, `reviews`, `image_url`, `url`, `price`) VALUES
('39.8274175724', '-86.1896065943', 'artmuseums', 1225, '4000 N Michigan Rd', '+13179231331', 'Newfields: A Place for Nature and the Arts', '4.5', 396, '6x6rR-SErwOo3xF2AzXVHA', ' newfields always has the best holiday events whether youre looking for christmas lights in the winter a fall fest to try new beers or a fun activity for we love newfields weve been members for a little over a year now and have greatly enjoyed visiting the museum throughout all four seasons every few weeks this is one of my favorite places in indianapolis get the membership if youre local and plan on going more than once their new beer garden is in a prime', 'https://s3-media2.fl.yelpcdn.com/bphoto/cOh78R0YG3yW503Z6V7qPg/o.jpg', 'https://www.yelp.com/biz/newfields-a-place-for-nature-and-the-arts-indianapolis?adjust_creative=13fm2IFOwwkkm0xoP1hczw&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_search&utm_source=13fm2IFOwwkkm0xoP1hczw', ''),
('39.7812428847', '-86.2407670706', 'pizza', 1233, '1067 N Main St', '+13177442826', 'Brozinni Pizzeria at Speedway', '4.5', 88, 'F5n57w6RaOCAB4bivNSs8A', ' wonderful addition to speedway im a big fan of brozinni pizza in general and while we miss the southside location its a good excuse to visit speedway we loved hanging out inside brozinni pizzerias covered multi tiered patio with fireplaces with our dog  what a cool space  we enjoyed their delicious delicious  awesome atmosphere pizza was so good  definitely coming back next time we are in town so close to the speedway as well', 'https://s3-media3.fl.yelpcdn.com/bphoto/Wnj38IgMDewqwaf7WPbOBA/o.jpg', 'https://www.yelp.com/biz/brozinni-pizzeria-at-speedway-speedway?adjust_creative=13fm2IFOwwkkm0xoP1hczw&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_search&utm_source=13fm2IFOwwkkm0xoP1hczw', '$$'),
('39.9127841652', '-86.1314165861', 'gyms', 1238, '1706 E 86th St', '+13172183928', 'Fit Flex Fly', '5.0', 16, 'E5cecsuxC11xDO9E3c93lA', ' i was there for the yelp event last night and i was thoroughly impressed by the facilities and the staff i used to work out pretty hard in college but fyi the spin  strength class is not just lifting weights on the bike its 30 mins of hard spin and another 30 mins of bootcamp style shiz what watch travelers calling anyone from out of town or in town this gym is the best gym i have been to on the road i travel a lot every month and to get my', 'https://s3-media2.fl.yelpcdn.com/bphoto/ZoRGh8jWub3iVekqYWTQlA/o.jpg', 'https://www.yelp.com/biz/fit-flex-fly-indianapolis?adjust_creative=13fm2IFOwwkkm0xoP1hczw&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_search&utm_source=13fm2IFOwwkkm0xoP1hczw', ''),
('39.7648071009', '-86.1685137465', 'stadiumsarenas', 1248, '501 W Maryland St', '+13175326778', 'Victory Field', '4.5', 225, 'kzxpl9HidQVMEuUoRVB7nA', ' ive seen dozens of games here over the years and it is just a great ballparkthey have lots of fun summer specials from firework friday thirsty thursday nice clean spacious and fan friendly the staff are great they really did dog days well with all the dogs in the outfield for a fairly big crowd it was we had a great time at the ballpark sitting in the outfield is such an easy option with kids we brought snacks and got food at her concession stand', 'https://s3-media4.fl.yelpcdn.com/bphoto/6E9eOlnjNMQ3gtbTh7HRmA/o.jpg', 'https://www.yelp.com/biz/victory-field-indianapolis?adjust_creative=13fm2IFOwwkkm0xoP1hczw&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_search&utm_source=13fm2IFOwwkkm0xoP1hczw', ''),
('39.7895200000', '-86.1887600000', 'football', 1256, '1502 W 16th St', '+13173277194', 'Kuntz Soccer Complex', '4.0', 1, 'pWd0jGZPaBFtDV3w85oKpQ', ' this is a very nice complex with a decent sized private parking lot there are two fifa regulation sized fields measuring 120 yards in length and 75 yards', 'https://s3-media2.fl.yelpcdn.com/bphoto/yOxso9JLy5HzqS7zjrqIag/o.jpg', 'https://www.yelp.com/biz/kuntz-soccer-complex-indianapolis?adjust_creative=13fm2IFOwwkkm0xoP1hczw&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_search&utm_source=13fm2IFOwwkkm0xoP1hczw', ''),
('39.7940537000', '-86.1459458000', 'parks', 1257, '1701 N Broadway St', '+13173277418', 'Martin Luther King Memorial Park', '5.0', 11, 'pqi4QOI1fC2kGeyxiW_jgw', ' i started my 2021 martin luther king jr holiday with a visit to indys martin luther king memorial park a park that marks the spot where indy residents great park with lots of stuff for kids to do there is an aquatic center next to it but hasnt been open for a while there is also a basketball court i could not have thought of a better day to visit this park then on mlks birthday i brought my kids as a teaching point for them as well this park has', 'https://s3-media1.fl.yelpcdn.com/bphoto/BWGUcT4Q0nMPp0VdSeBiTw/o.jpg', 'https://www.yelp.com/biz/martin-luther-king-memorial-park-indianapolis?adjust_creative=13fm2IFOwwkkm0xoP1hczw&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_search&utm_source=13fm2IFOwwkkm0xoP1hczw', ''),
('39.8832100000', '-86.0710600000', 'beerbar', 1279, '5711 E 71st St', '+13175164814', 'Roots Burger Bar', '4.0', 96, 'VEGvvazGmbukHqZyToVvYw', ' all the food  service has been great during my three visits thus far and kudos to our bartender the dev-meister sat in the bar and enjoyed the atmosphere you get a popcorn bucket when you walk in which is neat everything we got was delicious and our server was theres nothing better than spending a saturday evening in a cozy sports bar munching on some delicious bites and watching the wild card games on one of', 'https://s3-media4.fl.yelpcdn.com/bphoto/jojmr44rp3kUIHsAjcUUkw/o.jpg', 'https://www.yelp.com/biz/roots-burger-bar-indianapolis?adjust_creative=13fm2IFOwwkkm0xoP1hczw&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_search&utm_source=13fm2IFOwwkkm0xoP1hczw', '$$'),
('39.9259100000', '-86.0871100000', 'swimminglessons', 1499, '4825 E 96th St', '+13175593210', 'Aqua-Tots Swim Schools North Indianapolis', '5.0', 8, '7WHOFwMB_8Fxz2yyFDp-TQ', ' swim lessons here have quickly become a highlight of my gals week the staff is excellent and clearly are up to date on the best techniques for kids love aqua-tots my daughter started here at 10 months then had ear issues and was unable to continue recently at 5 years old we dove back in pun brooke is amazing  my kids have big time fear when it comes to swim class and brooke who is also the manager was so sweet and good with them she helped', 'https://s3-media1.fl.yelpcdn.com/bphoto/WyonxDJtlL2MoRW2b_FUqg/o.jpg', 'https://www.yelp.com/biz/aqua-tots-swim-schools-north-indianapolis-indianapolis', ''),
('39.7616130000', '-86.1543420000', 'italian', 1500, '339 South Delaware St', '+13176437400', 'Nesso', '4.5', 250, 'QaLr_-5abntoxH7Isbs0iA', ' this is one of my restaurants in indy their cocktails are absolutely amazing the food has always been delicious i hate that i always forget to take updating my previous review -- which was away back in oct 2018 about a month after they opened  we had a great time then celebrating an anniversary nesso holds a special place in our hearts the day we got married we had our first meal together at nesso theyre always so welcoming and kind the food is', 'https://s3-media2.fl.yelpcdn.com/bphoto/-Oclfrbcb_W0FdjYB8VNkg/o.jpg', 'https://www.yelp.com/biz/nesso-indianapolis-3', '$$$'),
('39.8611060000', '-86.1465300000', 'seafood', 1501, '5858 N College Ave', '+13175593259', 'Blupoint Oyster House', '4.5', 103, '6kMEt2jDpFHIt2fFuVczEw', ' indys devour promotion lead us to this oyster house restaurant has been renamed so thats a little confusing  the clams were excellent with plenty of the seafood is out of this world so so good however the restaurant seems to not be super organized- or maybe its short staffed we had a reservation let me start off by saying that my first experience at blupoint was stellar so im giving them an extra star or two for that this time however every', 'https://s3-media4.fl.yelpcdn.com/bphoto/g6QJM-Dtw2Q7ijzPC8MPyw/o.jpg', 'https://www.yelp.com/biz/blupoint-oyster-house-indianapolis', ''),
('39.7666240000', '-86.1648434000', 'breakfast_brunch', 1502, '350 West Maryland St', '+13174056100', 'Conners Kitchen + Bar', '4.0', 461, 'My9NAWShKtNqBYw7200qOw', ' had a wonderful lunch experience with fantastic food deviled eggs  fried chicken yum and even better service crystal always made sure we had strong 4 here on the food and overall experience located in the marriott so easy access if youre staying there as i was get right to the food i stayed at the hotel for a couple of days due to an convention i was hosting we sat at the bar and the night bartender was great and personable he gave', 'https://s3-media3.fl.yelpcdn.com/bphoto/nbQrO1r41Qpy97L92qIpnw/o.jpg', 'https://www.yelp.com/biz/conners-kitchen-bar-indianapolis-2', '$$'),
('39.7277133002', '-86.2510529423', 'steak', 1503, '2610 S Lynhurst Dr', '+13172430299', 'The Library Restaurant & Pub', '4.0', 479, 'qAn_A-pwh-SDBj6Sjff8Qw', ' my coworkers and i were having a tough time choosing a restaurant for dinner as there didnt seem to be many good options in plainfield there was a what a unique dining experience its not every day you find the opportunity to eat a full course meal in a library turned restaurant tried the fish  it just ok yes library with old books and food no excitement in place real boring average go to capital grille', 'https://s3-media3.fl.yelpcdn.com/bphoto/1LLdVUv0puDQM-GeKNN04g/o.jpg', 'https://www.yelp.com/biz/the-library-restaurant-and-pub-indianapolis', '$$'),
('39.8247210000', '-86.2433660000', 'mexican', 1504, '4920 W 38th St', '+13172912800', 'El Puerto de San Blas', '4.0', 90, 'XgWpo2hxLT1tn5sFDPr-dQ', ' best mexican seafood in town  possibly the onlyi love coming here for the shrimp empanadas and the paella  they give you ceviche with your chips and this is a stellar lunch experience if you enjoy ceviche or have ever been interested in trying it this is the place instead of salsa they bring out incredible this place was a bit out of my comfort zone as i dont usually eat seafood in indianapolis and ive never had this style of food before but', 'https://s3-media3.fl.yelpcdn.com/bphoto/Jq36DEeCrMZHiwgtw4haSg/o.jpg', 'https://www.yelp.com/biz/el-puerto-de-san-blas-indianapolis', '$$'),
('39.9060987371', '-86.0939471329', 'salad', 1505, '4335 E 82nd St', '+13175379815', 'CoreLife Eatery', '4.5', 356, 't07Pof3RG7i-4DdZ5pYdaQ', ' amazing food  if you are looking for an away from home healthier food choicesthat actually tastes yummythen head to corelife not only is the good my friend on her particular whole 30 diet said that the way the restaurant prepares their chicken was fine  we ate outside  i ordered the kids power plate special shout out to deidre they no longer serve the mediterranean but you can still special order it the falafel has a little kick that takes this over', 'https://s3-media1.fl.yelpcdn.com/bphoto/Xe_9eJHzXQBEWGPq1ONvDg/o.jpg', 'https://www.yelp.com/biz/corelife-eatery-indianapolis', '$$'),
('39.7611270000', '-86.1544180000', 'gyms', 1506, '430 S Alabama St', '+13179559622', 'Irsay Family YMCA at CityWay', '4.0', 104, 'IDpUwHufzAOl_9hftL7toQ', ' if you ever want to join any ymca know you can take a tour of the entire place before finding out which ymca is a good fit for you i needed to find one i have mixed feelings about this facility as someone who has been going to this gym for years i can say the only reason i continue to go here is simply my main gym that i use during the winter months because i usually just walk outdoors at the canal or around my neighborhood ample space for me to use the', 'https://s3-media2.fl.yelpcdn.com/bphoto/-LbPmC3-NQ8LDvSYWVis5A/o.jpg', 'https://www.yelp.com/biz/irsay-family-ymca-at-cityway-indianapolis-2', ''),
('39.9255360530', '-86.0898307000', 'seafood', 1507, '4705 E 96th St', '+13172183267', 'Mi gusto es VIP food', '4.5', 7, 'bHRdxBfIS2KJRGa2lN_rSg', ' outstanding quality of food the spicy shrimp was fresh and the heat level was like nothing ive had previously this place really strives for quality and great service  mojito spot onunique full of flavor tastings  very generous portions very fresh food from family owned and operated business the proportions are very good and the spices are just right its not your typical same thing', 'https://s3-media3.fl.yelpcdn.com/bphoto/Z5g5zsDd5cl0UChJQm_uxw/o.jpg', 'https://www.yelp.com/biz/mi-gusto-es-vip-food-indianapolis', ''),
('39.7639116347', '-86.1595572000', 'hotels', 1508, '40 W Jackson Pl', '+13176346664', 'Omni Severin Hotel', '3.5', 220, 'Gq0uY-nzwgHjlTGLSogKGg', ' i stayed here for a work conference and had a wonderful experience the room was nice and clean all the food was great - especially lunches and dinners great location downtown close the gainbridge fieldhouse the mall and restaurants very clean with a restaurant and bar on site there is also a starbucks oh omni severin what has happened to you this hotel used to be our 1 hotel in indy we stayed last night and ugh it was not good from check in to check', 'https://s3-media2.fl.yelpcdn.com/bphoto/O1Xp6HOtGBAziY0ogPNO8w/o.jpg', 'https://www.yelp.com/biz/omni-severin-hotel-indianapolis-2', '$$$');

-- --------------------------------------------------------

--
-- Table structure for table `recommendations`
--

CREATE TABLE `recommendations` (
  `API_ID` varchar(55) NOT NULL,
  `userID` varchar(50) NOT NULL,
  `value` decimal(15,14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tfidfs`
--

CREATE TABLE `tfidfs` (
  `API_ID` varchar(55) NOT NULL,
  `word` varchar(50) NOT NULL,
  `value` decimal(15,14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `trippois`
--

CREATE TABLE `trippois` (
  `id` int(11) NOT NULL,
  `API_ID` varchar(255) NOT NULL,
  `startTime` varchar(11) NOT NULL,
  `endTime` varchar(11) NOT NULL,
  `tripID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `id` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `name` varchar(55) NOT NULL,
  `lastModified` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(50) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `gender` varchar(10) DEFAULT NULL,
  `race` char(55) DEFAULT NULL,
  `age` int(3) DEFAULT NULL,
  `birthday` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `date`, `gender`, `race`, `age`, `birthday`) VALUES
(1, 'TestUser', '$2y$10$zHMHWHrV9goUhYhkA982wO4VxFNsRAJjsGxWIYbTGaP3hc0qAEtve', '2023-02-08 20:36:12', NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`userID`,`API_ID`);

--
-- Indexes for table `pois`
--
ALTER TABLE `pois`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `API_ID` (`API_ID`);

--
-- Indexes for table `recommendations`
--
ALTER TABLE `recommendations`
  ADD PRIMARY KEY (`API_ID`,`userID`);

--
-- Indexes for table `tfidfs`
--
ALTER TABLE `tfidfs`
  ADD PRIMARY KEY (`API_ID`,`word`);

--
-- Indexes for table `trippois`
--
ALTER TABLE `trippois`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pois`
--
ALTER TABLE `pois`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2851;

--
-- AUTO_INCREMENT for table `trippois`
--
ALTER TABLE `trippois`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=491;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

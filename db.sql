SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


--
-- Table structure for table `login`
--

CREATE TABLE IF NOT EXISTS `login` (
  `IPADD` int(10) unsigned NOT NULL,
  `DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `attempts` int(11) NOT NULL,
  `FAIL` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE IF NOT EXISTS `members` (
`memberID` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `username` varchar(32) NOT NULL DEFAULT '',
  `email` varchar(32) NOT NULL,
  `password` varchar(256) NOT NULL DEFAULT '',
  `lastlogin` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ipadd` int(11) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL,
  `isadmin` int(11) NOT NULL,
  `salt` varchar(25) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`memberID`, `name`, `username`, `email`, `password`, `lastlogin`, `ipadd`, `active`, `isadmin`, `salt`) VALUES
(1, 'admin', 'admin', 'd27884@trbvm.com', '$2a$12$1bf88fcf0c0145e75fa40u0XssRJBznB31jPRiKIgwmpO6Ri0VIQq', '2015-01-24 14:41:18', 2030499581, 1, 1, 'xNy}q*SukjXB9HSv`wHd-:^hH');

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE IF NOT EXISTS `tokens` (
  `token` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  `used` tinyint(1) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `login`
--
ALTER TABLE `login`
 ADD PRIMARY KEY (`IPADD`,`FAIL`,`DATE`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
 ADD PRIMARY KEY (`memberID`,`username`,`password`,`ipadd`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
 ADD PRIMARY KEY (`token`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
MODIFY `memberID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

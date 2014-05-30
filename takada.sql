

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `takadaDB`
--

-- --------------------------------------------------------

--
--
DROP TABLE IF EXISTS `Users`;

CREATE TABLE IF NOT EXISTS `Users` (
  `Id` int AUTO_INCREMENT NOT NULL,
  `Name` varchar(150) NOT NULL,
  `Email` varchar(150) NOT NULL,
  `Password` varchar(150) NOT NULL,
  `Salt` varchar(150) NOT NULL,
  `Location` varchar(300) NULL,
  `About` text NULL,
  `F-Music` text NULL,
  `F-Movies` text NULL,
  `F-Authors` text NULL,
  `F-Quotes` text NULL,
  `F-Books` text NULL,
  `Hates` varchar(300) NULL,
  `Loves` varchar(300) NULL,
  `AddedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  UNIQUE(`Email`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `UserSocialProfiles`;

CREATE TABLE IF NOT EXISTS `UserSocialProfiles` (
  `Id` int AUTO_INCREMENT  NOT NULL,
  `UserId` int  NOT NULL,
  `Network` varchar(30)  NOT NULL,
  `Link` varchar(50)  NOT NULL,
  INDEX(`UserId`),
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS `UsersInRole`;

CREATE TABLE IF NOT EXISTS `UsersInRole` (
  `Id` int AUTO_INCREMENT  NOT NULL,
  `UserId` int  NOT NULL,
  `RoleId` int  NOT NULL,
  INDEX(`UserId`),
  INDEX(`RoleId`),
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `Roles`;

CREATE TABLE IF NOT EXISTS `Roles` (
  `Id` int AUTO_INCREMENT  NOT NULL,
  `Name` varchar(150) NOT NULL,
  `Description` varchar(500) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `Follow`;

CREATE TABLE IF NOT EXISTS `Follow` (
  `Id` int AUTO_INCREMENT  NOT NULL,
  `UserId` int  NOT NULL,
  `FollowerId` int  NOT NULL,
  INDEX(`UserId`),
  INDEX(`FollowerId`),
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS `Groups`;

CREATE TABLE IF NOT EXISTS `Groups` (
  `Id` int AUTO_INCREMENT  NOT NULL,
  `CreatorId` int  NOT NULL,
  `CategoryId` int  NOT NULL,
  `Name` varchar(200) NOT NULL,
  `Description` varchar(500) NULL,
  `CreatedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (`Id`),
   INDEX (`CreatorId`),
   INDEX (`CategoryId`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `GroupCategory`;

CREATE TABLE IF NOT EXISTS `GroupCategory` (
  `Id` int AUTO_INCREMENT  NOT NULL,
  `Name` varchar(100)  NOT NULL,
  `AddedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Description` varchar(500)  NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `UsersInGroup`;

CREATE TABLE IF NOT EXISTS `UsersInGroup` (
  `Id` int AUTO_INCREMENT  NOT NULL,
  `UserId` int  NOT NULL,
  `GroupId` int  NOT NULL,
   INDEX(`UserId`),
  INDEX(`GroupId`),
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `Posts`;

CREATE TABLE IF NOT EXISTS `Posts` (
  `Id` int AUTO_INCREMENT  NOT NULL,
  `UserId` int  NOT NULL,
  `Likes` int NOT NULL DEFAULT 0,
  `Post` text  NOT NULL,
  `PostedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `Tags`;

CREATE TABLE IF NOT EXISTS `Tags` (
  `Id` int AUTO_INCREMENT  NOT NULL,
  `Name` varchar(100)  NOT NULL,
  `Description` text NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `Genre`;

CREATE TABLE IF NOT EXISTS `Genre` (
  `Id` int AUTO_INCREMENT  NOT NULL,
  `Name` varchar(100)  NOT NULL,
  `Description` text NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS `TagsInPost`;

CREATE TABLE IF NOT EXISTS `TagsInPost` (
  `Id` int AUTO_INCREMENT  NOT NULL,
  `TagId` int  NOT NULL,
  `PostId` int  NOT NULL,
  INDEX(`TagId`),
  INDEX(`PostId`),
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS `PostInGenre`;

CREATE TABLE IF NOT EXISTS `PostInGenre` (
  `Id` int AUTO_INCREMENT  NOT NULL,
  `PostId` int  NOT NULL,
  `GenreId` int  NOT NULL,
  INDEX(`PostId`),
  INDEX(`GenreId`),
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS `Books`;

CREATE TABLE IF NOT EXISTS `Books` (
  `Id` int AUTO_INCREMENT  NOT NULL,
  `Title` varchar(200)  NOT NULL,
  `Description` text ,
  `Summary` text ,
  `AuthorName` varchar(200)  NOT NULL,
  `ISBN` int ,
  `IsFeatured` boolean ,
  `AddedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `PublishedDate` varchar(15) NOT NULL,
  `CoverArt` varchar(100)  NOT NULL,
  `TotalPrice` int  NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `UsersBook`;

CREATE TABLE IF NOT EXISTS `UsersBook` (
  `Id` int AUTO_INCREMENT  NOT NULL,
  `UserId` int  NOT NULL,
  `BookId` int  NOT NULL,
  `Review` varchar(200)  NOT NULL,
  `Endorse` int NOT NULL DEFAULT 0,
  INDEX(`UserId`),
  INDEX(`BookId`),
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `TagsInBook`;

CREATE TABLE IF NOT EXISTS `TagsInBook` (
  `Id` int AUTO_INCREMENT  NOT NULL,
  `TagId` int  NOT NULL,
  `BookId` int  NOT NULL,
  INDEX(`TagId`),
  INDEX(`BookId`),
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS `BooksInGenre`;

CREATE TABLE IF NOT EXISTS `BooksInGenre` (
  `Id` int AUTO_INCREMENT  NOT NULL,
  `BookId` int  NOT NULL,
  `GenreId` int  NOT NULL,
  INDEX(`BookId`),
  INDEX(`GenreId`),
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM;



DROP TABLE IF EXISTS `Quotes`;

CREATE TABLE IF NOT EXISTS `Quotes` (
  `Id` int AUTO_INCREMENT  NOT NULL,
  `Who` varchar(100)  NOT NULL,
  `Quote` varchar(500),
  `AddedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `Comments`;

CREATE TABLE IF NOT EXISTS `Comments` (
  `Id` int AUTO_INCREMENT  NOT NULL,
  `UserId` int  NOT NULL,
  `Comment` int  NOT NULL,
  `AddedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Likes` int  NOT NULL DEFAULT 0,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `Discussion`;

CREATE TABLE IF NOT EXISTS `Discussion` (
  `Id` int AUTO_INCREMENT  NOT NULL,
  `Title` varchar(100)  NOT NULL,
  `CreatorId` int  NOT NULL,
  `GroupId` int  NOT NULL,
  `Description` varchar(50) ,
  `Likes` int  NOT NULL DEFAULT 0,
  `CreatedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `CommentsInDiscussion`;

CREATE TABLE IF NOT EXISTS `CommentsInDiscussion` (
  `Id` int AUTO_INCREMENT  NOT NULL,
  `CommentId` int  NOT NULL,
  `DiscussionId` int  NOT NULL,
  INDEX(`CommentId`),
  INDEX(`DiscussionId`),
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `CommentsInPost`;

CREATE TABLE IF NOT EXISTS `CommentsInPost` (
  `Id` int AUTO_INCREMENT  NOT NULL,
  `CommentId` int  NOT NULL,
  `PostId` int  NOT NULL,
  INDEX(`CommentId`),
  INDEX(`PostId`),
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM;


/*
For Activity Table
verb tells me the action taken eg read, added  etc.SourceType tell me which object the action was taken 
eg book, comment, post and SourceId tells me the Id of the source type. In read a book, read is the verb, 
book is the SourceType and the Id of the book is the SourceId.

The ParentId/ParentType are useful - they tell me what the activity is related to. If a post was commented, 
then ParentId/ParentType would tell me that the activity relates to a Post (Type) with a given primary key (Id)

UserId and  time will be INDEXED for quick retrieval of record. 
To get Activity of followers, query for activities that are UserId IN (...friends...) AND time > some-cutoff-point.
To get Activity of a single User, query for activities that are UserId = ... AND time > some-cutoff-point.
*/

DROP TABLE IF EXISTS `Activity`;

CREATE TABLE IF NOT EXISTS `Activity` (
  `Id` int AUTO_INCREMENT  NOT NULL,
  `UserId` int  NOT NULL,
  `verb` varchar(100) NOT Null,
  `SourceType` varchar(100) NOT Null,
  `SourceId` int  NOT NULL,
  `ParentType` varchar(100) Null,
  `ParentId` int NULL,
  `Time` int NOT NULL,
  INDEX(`UserId`),
  INDEX(`Time`),
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM;




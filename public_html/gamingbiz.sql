drop table CEO cascade constraints;
drop table COMPANY cascade constraints;
drop table CUSTOMER cascade constraints;
drop table CUSTOMERSPENDING cascade constraints;
drop table CONSOLESBOUGHT cascade constraints;
drop table CONSOLESSOLD cascade constraints;
drop table DEVELOPER cascade constraints;
drop table EMPLOYEE cascade constraints;
drop table GAMEPRODUCED cascade constraints;
drop table GAMERATING cascade constraints;
drop table GAMESIZE cascade constraints;
drop table GAMESSOLD cascade constraints;
drop table GAMESBOUGHT cascade constraints;
drop table MEMBERSHIPDETAILS cascade constraints;
drop table MEMBERSHIPOWNED cascade constraints;
drop table REVIEWER cascade constraints;
drop table REVIEWS cascade constraints;
drop table STOREFRONT cascade constraints;
drop table WORKSFOR cascade constraints;

CREATE TABLE MembershipDetails
(
    membershipLevel  char(30),
    personalDiscount int,
    PRIMARY KEY (membershipLevel)
);

CREATE TABLE MembershipOwned
(
    totalSpent      int      DEFAULT 0,
    membershipLevel char(30),
    PRIMARY KEY (totalSpent),
    FOREIGN KEY (membershipLevel) REFERENCES MembershipDetails
);


CREATE TABLE CustomerSpending
(
	spentOnGames    int,
	spentOnConsoles	int,
	totalSpent		int,
	PRIMARY KEY (spentOnGames, spentOnConsoles),
	FOREIGN KEY (totalSpent) REFERENCES MembershipOwned
);

CREATE TABLE Customer
(
    cid             int,
    firstName       char(30),
    lastName        char(30),
    phoneNumber     int UNIQUE,
    email           varchar(80) UNIQUE,
    spentOnGames    int DEFAULT 0,
    spentOnConsoles int DEFAULT 0,
    PRIMARY KEY (cid),
    UNIQUE (phoneNumber, email),
    FOREIGN KEY (spentOnGames, spentOnConsoles) REFERENCES CustomerSpending
        ON DELETE SET NULL
);

CREATE TABLE StoreFront
(
    storeID   int,
    storeName varchar(30),
    location  char(30),
    PRIMARY KEY (storeID)
);

CREATE TABLE ConsolesBought
(
    sinNumber   int,
    consoleName varchar(30),
    releaseDate date,
    cid         int NOT NULL,
    ownedSince  date,
    price       int NOT NULL,
    PRIMARY KEY (sinNumber),
    FOREIGN KEY (cid) REFERENCES Customer
);

CREATE TABLE ConsolesSold
(
    sinNumber int,
    storeID   int,
    price     int NOT NULL,
    PRIMARY KEY (sinNumber, storeID),
    FOREIGN KEY (storeID) REFERENCES StoreFront ON DELETE CASCADE,
    FOREIGN KEY (sinNumber) REFERENCES ConsolesBought ON DELETE CASCADE
);

CREATE TABLE Employee
(
    employeeID int,
    position   varchar(30) NOT NULL,
    firstName  char(30),
    lastName   char(30),
    PRIMARY KEY (employeeID)
);

CREATE TABLE Company
(
    companyName varchar(30),
    companyID   int,
    employeeID  int NOT NULL UNIQUE,
    PRIMARY KEY (companyID),
    FOREIGN KEY (employeeID) REFERENCES Employee
);

CREATE TABLE GameSize
(
    gameLength int,
    memorySize int,
    PRIMARY KEY (gameLength)
);

CREATE TABLE GameRating
(
    genre      varchar(30),
    minimumAge int,
    ESRBRating varchar(20),
    PRIMARY KEY (genre, minimumAge)
);

CREATE TABLE GameProduced
(
    gameID        int,
    companyID     int,
    name          varchar(30),
    releaseDate   date,
    genre         varchar(30),
    numberPlayers int,
    minimumAge    int,
    gameLength    int,
    PRIMARY KEY (gameID, companyID),
    FOREIGN KEY (companyID) REFERENCES Company ON DELETE CASCADE,
    FOREIGN KEY (gameLength) REFERENCES GameSize,
    FOREIGN KEY (genre, minimumAge) REFERENCES GameRating
);

CREATE TABLE GamesBought
(
    cid       int,
    gameID    int,
    companyID int,
    price     int NOT NULL,
    PRIMARY KEY (cid, gameID, companyID),
    FOREIGN KEY (cid) REFERENCES Customer,
    FOREIGN KEY (gameID, companyID) REFERENCES GameProduced
);

CREATE TABLE GamesSold
(
    storeID   int,
    gameID    int,
    companyID int,
    price     int NOT NULL,
    PRIMARY KEY (storeID, gameID, companyID),
    FOREIGN KEY (storeID) REFERENCES StoreFront ON DELETE CASCADE,
    FOREIGN KEY (gameID, companyID) REFERENCES GameProduced ON DELETE CASCADE
);


CREATE TABLE Reviewer
(
    firstName  char(30),
    lastName   char(30),
    reviewerID int,
    PRIMARY KEY (reviewerID)
);

CREATE TABLE Reviews
(
    reviewerID int,
    gameID     int,
    companyID  int,
    rating     int NOT NULL,
    website    varchar(80),
    PRIMARY KEY (reviewerID, gameID, companyID),
    FOREIGN KEY (reviewerID) REFERENCES Reviewer,
    FOREIGN KEY (gameID, companyID) REFERENCES GameProduced
);

CREATE TABLE CEO
(
    companyID  int UNIQUE,
    employeeID int,
    PRIMARY KEY (employeeID),
    FOREIGN KEY (companyID) REFERENCES Company ON DELETE CASCADE,
    FOREIGN KEY (employeeID) REFERENCES Employee ON DELETE CASCADE
);


CREATE TABLE Developer
(
    devID      int UNIQUE,
    employeeID int,
    PRIMARY KEY (employeeID),
    FOREIGN KEY (employeeID) REFERENCES Employee ON DELETE CASCADE
);

CREATE TABLE WorksFor
(
    employeeID int,
    companyID  int,
    since      date NOT NULL,
    salary     int  NOT NULL,
    PRIMARY KEY (employeeID, companyID),
    FOREIGN KEY (employeeID) REFERENCES Employee ON DELETE CASCADE,
    FOREIGN KEY (companyID) REFERENCES Company ON DELETE CASCADE
);

INSERT INTO MembershipDetails VALUES('bronze', 10);
INSERT INTO MembershipDetails VALUES('silver', 15);
INSERT INTO MembershipDetails VALUES('gold', 20);
INSERT INTO MembershipDetails VALUES('diamond', 25);
INSERT INTO MembershipDetails VALUES('platinum', 30);

INSERT INTO MembershipOwned VALUES(10, 'bronze');
INSERT INTO MembershipOwned VALUES(250, 'silver');
INSERT INTO MembershipOwned VALUES(500, 'gold');
INSERT INTO MembershipOwned VALUES(750, 'diamond');
INSERT INTO MembershipOwned VALUES(1000, 'platinum');

INSERT INTO CustomerSpending VALUES(10, 100, 10);
INSERT INTO CustomerSpending VALUES(100, 500, 500);
INSERT INTO CustomerSpending VALUES(50, 250, 250);
INSERT INTO CustomerSpending VALUES(70, 300, 250);
INSERT INTO CustomerSpending VALUES(500, 1000, 1000);

INSERT INTO Customer VALUES(123, 'Emily', 'Lee', 6041234567, 'elee@gmail.com', 10, 100);
INSERT INTO Customer VALUES(124, 'Amanda', 'Lee', 6043214567, 'alee@gmail.com', 100, 500);
INSERT INTO Customer VALUES(125, 'John', 'Smith', 6041237567, 'jsmith@gmail.com', 50, 250);
INSERT INTO Customer VALUES(126, 'Jane', 'Doe', 6041234897, 'jdoe@gmail.com', 70, 300);
INSERT INTO Customer VALUES(127, 'Alex', 'Summer', 6043234567, 'asummer@gmail.com', 500, 1000);

INSERT INTO ConsolesBought VALUES (10000000, 'Xbox 360', DATE'2010-12-05', 123, DATE'2012-06-27', 278);
INSERT INTO ConsolesBought VALUES (10000001, 'PS3', DATE'2011-05-07', 124, DATE'2015-03-19', 300);
INSERT INTO ConsolesBought VALUES (10000002, 'Nintendo Wii', DATE'2008-03-08', 125, DATE'2008-04-16', 190);
INSERT INTO ConsolesBought VALUES (10000003, 'PS5', DATE'2016-11-15', 126, DATE'2019-06-07', 540);
INSERT INTO ConsolesBought VALUES (10000004, 'Nintendo Switch', DATE'2015-09-21', 127, DATE'2017-10-27', 320);

INSERT INTO StoreFront VALUES (001, 'Vancouver Games', 'Vancouver');
INSERT INTO StoreFront VALUES (002, 'Surrey Games', 'Surrey');
INSERT INTO StoreFront VALUES (003, 'Kitsilano Games', 'Kitsilano');
INSERT INTO StoreFront VALUES (004, 'Guildford Games', 'Guildford');
INSERT INTO StoreFront VALUES (005, 'Washington Games', 'Washington');

INSERT INTO ConsolesSold VALUES (10000000, 001, 278);
INSERT INTO ConsolesSold VALUES (10000001, 002, 300);
INSERT INTO ConsolesSold VALUES (10000002, 003, 190);
INSERT INTO ConsolesSold VALUES (10000003, 004, 540);
INSERT INTO ConsolesSold VALUES (10000004, 005, 320);

INSERT INTO Employee VALUES(121, 'developer', 'Jane', 'Doe'); 
INSERT INTO Employee VALUES(122, 'ceo', 'Amanda', 'Doe'); 
INSERT INTO Employee VALUES(123, 'ceo', 'Hannah', 'Doe');
INSERT INTO Employee VALUES(124, 'ceo', 'Will', 'Doe');
INSERT INTO Employee VALUES(125, 'ceo', 'Derek', 'Doe');
INSERT INTO Employee VALUES(126, 'ceo', 'Rupert', 'Doe'); 
INSERT INTO Employee VALUES(131, 'developer', 'John', 'Doe'); 
INSERT INTO Employee VALUES(141, 'developer', 'Henry', 'Gold'); 
INSERT INTO Employee VALUES(151, 'developer', 'Charles', 'Kim');
INSERT INTO Employee VALUES(161, 'developer', 'Timothy', 'Kim');

INSERT INTO Company VALUES('Nintendo', 200, 122);
INSERT INTO Company VALUES('Sega', 201, 123);
INSERT INTO Company VALUES('EA', 202, 124);
INSERT INTO Company VALUES('Ubisoft', 203, 125);
INSERT INTO Company VALUES('Sony', 204, 126);

INSERT INTO CEO VALUES(200, 122);
INSERT INTO CEO VALUES(201, 123);
INSERT INTO CEO VALUES(202, 124);
INSERT INTO CEO VALUES(203, 125);
INSERT INTO CEO VALUES(204, 126);

INSERT INTO Developer VALUES(12, 121);
INSERT INTO Developer VALUES(13, 131);
INSERT INTO Developer VALUES(14, 141);
INSERT INTO Developer VALUES(17, 151);
INSERT INTO Developer VALUES(18, 161);

INSERT INTO WorksFor VALUES(121, 200, DATE'2012-08-21', 81000);
INSERT INTO WorksFor VALUES(131, 201, DATE'2018-04-21', 54000);
INSERT INTO WorksFor VALUES(141, 202, DATE'2019-02-11', 78000);
INSERT INTO WorksFor VALUES(151, 203, DATE'2012-03-25', 98000);
INSERT INTO WorksFor VALUES(161, 204, DATE'2020-06-23', 62000);

INSERT INTO GameSize VALUES(50, 4);
INSERT INTO GameSize VALUES(60, 6);
INSERT INTO GameSize VALUES(70, 8);
INSERT INTO GameSize VALUES(80, 10);
INSERT INTO GameSize VALUES(90, 12);

INSERT INTO GameRating VALUES('Adventure', 12, 'T');
INSERT INTO GameRating VALUES('Action', 7, 'E');
INSERT INTO GameRating VALUES('Action', 12, 'T');
INSERT INTO GameRating VALUES('RPG', 12, 'T');
INSERT INTO GameRating VALUES('RPG', 18, 'M');

INSERT INTO GameProduced VALUES(1001, 200, 'Zelda', DATE'2008-07-11', 'RPG', 1, 12, 50);
INSERT INTO GameProduced VALUES(1002, 201, 'Splatoon', DATE'2012-08-12', 'Action', 4, 7, 60);
INSERT INTO GameProduced VALUES(1003, 202, 'Madden', DATE'2013-07-11', 'Action', 1, 12, 70);
INSERT INTO GameProduced VALUES(1004, 203, 'Assassin Creed', DATE'2008-11-11', 'RPG', 1, 18, 80);
INSERT INTO GameProduced VALUES(1005, 204, 'Spiderman', DATE'2018-07-13', 'Adventure', 1, 12, 90);

INSERT INTO GamesSold VALUES(001, 1001, 200, 60);
INSERT INTO GamesSold VALUES(002, 1002, 201, 40);
INSERT INTO GamesSold VALUES(003, 1003, 202, 50); 
INSERT INTO GamesSold VALUES(004, 1004, 203, 120);
INSERT INTO GamesSold VALUES(005, 1005, 204, 90); 

INSERT INTO GamesBought VALUES(123, 1001, 200, 60);
INSERT INTO GamesBought VALUES(124, 1002, 201, 40);
INSERT INTO GamesBought VALUES(125, 1003, 202, 50);
INSERT INTO GamesBought VALUES(126, 1004, 203, 120);
INSERT INTO GamesBought VALUES(127, 1005, 204, 90);

INSERT INTO Reviewer VALUES('John', 'Smith', 00001); 
INSERT INTO Reviewer VALUES('Jane', 'Doe', 00002); 
INSERT INTO Reviewer VALUES('Amanda', 'Smith', 00003); 
INSERT INTO Reviewer VALUES('Henry', 'Guy', 00004); 
INSERT INTO Reviewer VALUES('Harold', 'Lee', 00005); 

INSERT INTO Reviews VALUES (00001, 1001, 200, 2, 'reviewwebsite1.com');
INSERT INTO Reviews VALUES (00002, 1002, 201, 4, 'reviewwebsite2.com');
INSERT INTO Reviews VALUES (00003, 1003, 202, 6, 'reviewwebsite3.com'); 
INSERT INTO Reviews VALUES (00004, 1004, 203, 8, 'reviewwebsite4.com'); 
INSERT INTO Reviews VALUES (00005, 1005, 204, 10, 'reviewwebsite5.com');

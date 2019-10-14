
update hotels SET pic_1 = 1 WHERE length(pic_1) > 0;
update hotels SET pic_2 = 1 WHERE length(pic_2) > 0;
update hotels SET pic_3 = 1 WHERE length(pic_3) > 0;
update hotels SET pic_4 = 1 WHERE length(pic_4) > 0;
update hotels SET pic_5 = 1 WHERE length(pic_5) > 0;
update hotels SET pic_1 = 0 WHERE length(pic_1) = 0;
update hotels SET pic_2 = 0 WHERE length(pic_2) = 0;
update hotels SET pic_3 = 0 WHERE length(pic_3) = 0;
update hotels SET pic_4 = 0 WHERE length(pic_4) = 0;
update hotels SET pic_5 = 0 WHERE length(pic_5) = 0;
ALTER TABLE hotels MODIFY COLUMN pic_1 Boolean DEFAULT 0;
ALTER TABLE hotels MODIFY COLUMN pic_2 Boolean DEFAULT 0;
ALTER TABLE hotels MODIFY COLUMN pic_3 Boolean DEFAULT 0;
ALTER TABLE hotels MODIFY COLUMN pic_4 Boolean DEFAULT 0;
ALTER TABLE hotels MODIFY COLUMN pic_5 Boolean DEFAULT 0;

update amaken SET pic_1 = 1 WHERE length(pic_1) > 0;
update amaken SET pic_2 = 1 WHERE length(pic_2) > 0;
update amaken SET pic_3 = 1 WHERE length(pic_3) > 0;
update amaken SET pic_4 = 1 WHERE length(pic_4) > 0;
update amaken SET pic_5 = 1 WHERE length(pic_5) > 0;
update amaken SET pic_1 = 0 WHERE length(pic_1) = 0;
update amaken SET pic_2 = 0 WHERE length(pic_2) = 0;
update amaken SET pic_3 = 0 WHERE length(pic_3) = 0;
update amaken SET pic_4 = 0 WHERE length(pic_4) = 0;
update amaken SET pic_5 = 0 WHERE length(pic_5) = 0;
ALTER TABLE amaken MODIFY COLUMN pic_1 Boolean DEFAULT 0;
ALTER TABLE amaken MODIFY COLUMN pic_2 Boolean DEFAULT 0;
ALTER TABLE amaken MODIFY COLUMN pic_3 Boolean DEFAULT 0;
ALTER TABLE amaken MODIFY COLUMN pic_4 Boolean DEFAULT 0;
ALTER TABLE amaken MODIFY COLUMN pic_5 Boolean DEFAULT 0;


update majara SET pic_1 = 1 WHERE length(pic_1) > 0;
update majara SET pic_2 = 1 WHERE length(pic_2) > 0;
update majara SET pic_3 = 1 WHERE length(pic_3) > 0;
update majara SET pic_4 = 1 WHERE length(pic_4) > 0;
update majara SET pic_5 = 1 WHERE length(pic_5) > 0;
update majara SET pic_1 = 0 WHERE length(pic_1) = 0;
update majara SET pic_2 = 0 WHERE length(pic_2) = 0;
update majara SET pic_3 = 0 WHERE length(pic_3) = 0;
update majara SET pic_4 = 0 WHERE length(pic_4) = 0;
update majara SET pic_5 = 0 WHERE length(pic_5) = 0;
ALTER TABLE majara MODIFY COLUMN pic_1 Boolean DEFAULT 0;
ALTER TABLE majara MODIFY COLUMN pic_2 Boolean DEFAULT 0;
ALTER TABLE majara MODIFY COLUMN pic_3 Boolean DEFAULT 0;
ALTER TABLE majara MODIFY COLUMN pic_4 Boolean DEFAULT 0;
ALTER TABLE majara MODIFY COLUMN pic_5 Boolean DEFAULT 0;



update restaurant SET pic_1 = 1 WHERE length(pic_1) > 0;
update restaurant SET pic_2 = 1 WHERE length(pic_2) > 0;
update restaurant SET pic_3 = 1 WHERE length(pic_3) > 0;
update restaurant SET pic_4 = 1 WHERE length(pic_4) > 0;
update restaurant SET pic_5 = 1 WHERE length(pic_5) > 0;
update restaurant SET pic_1 = 0 WHERE length(pic_1) = 0;
update restaurant SET pic_2 = 0 WHERE length(pic_2) = 0;
update restaurant SET pic_3 = 0 WHERE length(pic_3) = 0;
update restaurant SET pic_4 = 0 WHERE length(pic_4) = 0;
update restaurant SET pic_5 = 0 WHERE length(pic_5) = 0;
ALTER TABLE restaurant MODIFY COLUMN pic_1 Boolean DEFAULT 0;
ALTER TABLE restaurant MODIFY COLUMN pic_2 Boolean DEFAULT 0;
ALTER TABLE restaurant MODIFY COLUMN pic_3 Boolean DEFAULT 0;
ALTER TABLE restaurant MODIFY COLUMN pic_4 Boolean DEFAULT 0;
ALTER TABLE restaurant MODIFY COLUMN pic_5 Boolean DEFAULT 0;


update adab SET pic_1 = 1 WHERE length(pic_1) > 0;
update adab SET pic_2 = 1 WHERE length(pic_2) > 0;
update adab SET pic_3 = 1 WHERE length(pic_3) > 0;
update adab SET pic_4 = 1 WHERE length(pic_4) > 0;
update adab SET pic_5 = 1 WHERE length(pic_5) > 0;
update adab SET pic_1 = 0 WHERE length(pic_1) = 0;
update adab SET pic_2 = 0 WHERE length(pic_2) = 0;
update adab SET pic_3 = 0 WHERE length(pic_3) = 0;
update adab SET pic_4 = 0 WHERE length(pic_4) = 0;
update adab SET pic_5 = 0 WHERE length(pic_5) = 0;
ALTER TABLE adab MODIFY COLUMN pic_1 Boolean DEFAULT 0;
ALTER TABLE adab MODIFY COLUMN pic_2 Boolean DEFAULT 0;
ALTER TABLE adab MODIFY COLUMN pic_3 Boolean DEFAULT 0;
ALTER TABLE adab MODIFY COLUMN pic_4 Boolean DEFAULT 0;
ALTER TABLE adab MODIFY COLUMN pic_5 Boolean DEFAULT 0;

ALTER TABLE log add COLUMN alt VARCHAR(100) NULL DEFAULT NULL;

ALTER TABLE `config`
ADD `similarNoFollow` BOOLEAN NOT NULL DEFAULT FALSE AFTER `childInnerMin`,
ADD `nearbyNoFollow` BOOLEAN NOT NULL DEFAULT FALSE AFTER `similarNoFollow`,
ADD `panelNoFollow` BOOLEAN NOT NULL DEFAULT FALSE AFTER `nearbyNoFollow`,
ADD `profileNoFollow` BOOLEAN NOT NULL DEFAULT FALSE AFTER `panelNoFollow`,
ADD `writeCommentNoFollow` BOOLEAN NOT NULL DEFAULT FALSE AFTER `profileNoFollow`,
ADD `myTripNoFollow` BOOLEAN NOT NULL DEFAULT FALSE AFTER `writeCommentNoFollow`,
ADD `bookmarkNoFollow` BOOLEAN NOT NULL DEFAULT FALSE AFTER `myTripNoFollow`,
ADD `policyNoFollow` BOOLEAN NOT NULL DEFAULT FALSE AFTER `bookmarkNoFollow`,
ADD `hotelListNoFollow` BOOLEAN NOT NULL DEFAULT FALSE AFTER `policyNoFollow`,
ADD `externalSiteNoFollow` BOOLEAN NOT NULL DEFAULT FALSE AFTER `hotelListNoFollow`,
ADD `facebookNoFollow` BOOLEAN NOT NULL DEFAULT FALSE AFTER `externalSiteNoFollow`,
ADD `telegramNoFollow` BOOLEAN NOT NULL DEFAULT FALSE AFTER `facebookNoFollow`,
ADD `googlePlusNoFollow` BOOLEAN NOT NULL DEFAULT FALSE AFTER `telegramNoFollow`,
ADD `otherProfileNoFollow` BOOLEAN NOT NULL DEFAULT FALSE AFTER `googlePlusNoFollow`,
ADD `allCommentsNoFollow` BOOLEAN NOT NULL DEFAULT FALSE AFTER `otherProfileNoFollow`,
ADD `allAnsNoFollow` BOOLEAN NOT NULL DEFAULT FALSE AFTER `allCommentsNoFollow`;
ALTER TABLE `config` ADD `twitterNoFollow` BOOLEAN NOT NULL DEFAULT FALSE AFTER `allAnsNoFollow`;
ALTER TABLE `config` ADD `aparatNoFollow` BOOLEAN NOT NULL DEFAULT FALSE AFTER `twitterNoFollow`,
ADD `instagramNoFollow` BOOLEAN NOT NULL DEFAULT FALSE AFTER `aparatNoFollow`,
ADD `gardeshnameNoFollow` BOOLEAN NOT NULL DEFAULT FALSE AFTER `instagramNoFollow`,
ADD `bogenNoFollow` BOOLEAN NOT NULL DEFAULT FALSE AFTER `gardeshnameNoFollow`,
ADD `linkedinNoFollow` BOOLEAN NOT NULL DEFAULT FALSE AFTER `bogenNoFollow`;
ALTER TABLE `config` ADD `pinterestNoFollow` BOOLEAN NOT NULL DEFAULT FALSE AFTER `linkedinNoFollow`;
ALTER TABLE `config` ADD `backToHotelListNoFollow` BOOLEAN NOT NULL DEFAULT FALSE AFTER `pinterestNoFollow`;
ALTER TABLE `config` ADD `showReviewNoFollow` BOOLEAN NOT NULL DEFAULT FALSE AFTER `backToHotelListNoFollow`;

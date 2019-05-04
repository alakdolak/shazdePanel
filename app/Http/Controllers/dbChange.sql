
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
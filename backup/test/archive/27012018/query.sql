
SELECT distinct created_timestamp
FROM property_detail 
WHERE posted_date < SUBDATE( CURRENT_TIMESTAMP, INTERVAL 4 HOUR)
and location ='Bellandur'

alter table property_detail
drop column created_timestamp timestamp

alter table property_detail
add created_timestamp timestamp

select * from property_detail

select count(1) from property_detail

select count(1) from property_detail
where posted_date>=CURRENT_DATE - INTERVAL 3 DAY

--8

select distinct t.posted_date from property_detail t


truncate table property_detail

SELECT * FROM users WHERE created >= NOW();
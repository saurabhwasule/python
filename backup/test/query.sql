
select * from locality
where region='east'
and m_name is null

delete from locality
where id=11988

update locality
set m_name='Mahadevpur'
where name='Mahadevpura'


SELECT max(p.created_timestamp)
FROM property_detail p
inner  join search_detail s
on(p.created_timestamp=p.created_timestamp)
WHERE  p.location ='marathahalli'
and s.posted_since_indays>=3

SELECT distinct source,heading,price,super_buildup,posted_date,owner_dealer,owner_dealer_name AS name,PROPERTY_DETAIL_URL 
FROM property_detail 
where 1=1 
AND location='marathahalli' 
AND created_timestamp = '2018-01-31 11:32:57'
order by posted_date desc LIMIT 0,13


select source,created_timestamp,count(1)
from property_detail
group by source,created_timestamp
order by 2 desc

select * from property_detail

select count(1) from property_detail

select count(1) from property_detail
where posted_date>=CURRENT_DATE - INTERVAL 3 DAY


select distinct t.posted_date from property_detail t


truncate table property_detail
truncate table search_detail


SELECT * FROM users WHERE created >= NOW();
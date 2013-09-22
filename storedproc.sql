DROP PROCEDURE IF EXISTS GetFulfillList 
go
CREATE PROCEDURE GetFulfillList(IN orderAmt DOUBLE, IN direction VARCHAR(4))
BEGIN
DECLARE buy, offer_id INT DEFAULT 0;

IF direction = "buy" THEN
    SET buy = FALSE;
ELSE
    SET buy = TRUE;
END IF;

DROP TEMPORARY TABLE IF EXISTS offer_list;
CREATE TEMPORARY TABLE offer_list AS
    SELECT * FROM offer WHERE active = 1 AND isBuy = buy;

DROP TABLE IF EXISTS current_order_fulfillment;
CREATE TABLE current_order_fulfillment LIKE offer;

WHILE (SELECT COUNT(id) FROM offer_list) > 0 AND ((SELECT SUM(amount) FROM current_order_fulfillment) < orderAmt OR
 (SELECT SUM(amount) FROM current_order_fulfillment) IS NULL)
DO

IF direction = "buy" THEN
    SELECT id FROM offer_list ORDER BY price ASC LIMIT 1 INTO offer_id;
ELSE
    SELECT id FROM offer_list ORDER BY price DESC LIMIT 1 INTO offer_id;
END IF;

INSERT INTO current_order_fulfillment SELECT * FROM offer_list WHERE id = offer_id;
DELETE FROM offer_list WHERE id = offer_id;

END WHILE;

IF direction = "buy" THEN
    SELECT * FROM current_order_fulfillment ORDER BY price ASC;
ELSE
    SELECT * FROM current_order_fulfillment ORDER BY price DESC;
END IF;

DROP TABLE IF EXISTS current_order_fulfillment;
END
USE `elixiatech`;
DROP procedure IF EXISTS `getInvoiceReminders`;

DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `getInvoiceReminders`(
	IN todayParam DATE
)
BEGIN
	SELECT ir.inv_rem_id,c.customercompany,it.inv_type_name,
		ir.remarks,ip.prod_name,ir.expectedInvDate,ir.inv_amt as invoiceAmount,
		ir.inv_desc,ir.invoiceid,
        (CASE WHEN ir.contract_type=3
			THEN 'N.A.'
		ELSE ir.amount
		END
        )as amount,
        (CASE WHEN ir.contract_type=3
			THEN 'N.A.'
		ELSE
			ir.amc_amount
        END)as amc_amount,ir.reminder_date,ir.start_date,ir.end_date,ic.cycle_name,ir.invoice_generated,
        l.ledgername
    
    FROM invoice_reminders ir
    
    LEFT JOIN customer c ON c.customerno = ir.customerno
    LEFT JOIN invoice_type it ON it.inv_type_id=ir.contract_type
    LEFT JOIN invoice_products ip ON ip.prod_id = ir.productId
    LEFT JOIN invoice_cycles ic ON ic.cycle_id = ir.cycle 
    LEFT JOIN speed.ledger l ON l.ledgerid = ir.ledgerno
    WHERE ir.reminder_date <= todayParam AND ir.invoice_generated = 0 AND ir.isdeleted = 0; 
END$$

DELIMITER ;

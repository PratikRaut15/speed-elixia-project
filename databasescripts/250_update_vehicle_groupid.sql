-- Insert SQL here.


update vehicle set groupid=1485 where vehicleid in (2893, 2894, 2895, 2896, 2897, 2898, 2899, 2900, 2901, 2902, 2903, 2904, 2905, 2906, 2907, 2908, 2909, 2910, 2911, 2912, 2913, 2914, 2915, 2916, 2917, 2918, 2919, 2920) and customerno=151;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 249, NOW(), 'Akhil','update groupid of vehicle table of localbanya');

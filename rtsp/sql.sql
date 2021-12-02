delimiter |

CREATE TRIGGER tk_insert AFTER INSERT ON marks
  FOR EACH ROW
  BEGIN
  
  SET @DATE = (SELECT `date` FROM marks WHERE `id`=new.`id`);
  
  SET @FORM = (SELECT `grup`.`form` FROM `users_info`,`grup` WHERE `users_info`.`grup`=`grup`.`id` 
               AND `users_info`.`id`=new.`ui_id` limit 1);
               
  SET @COURSE = (SELECT `grup`.`course` FROM `users_info`,`grup` WHERE `users_info`.`grup`=`grup`.`id` 
               AND `users_info`.`id`=new.`ui_id` limit 1)*2;
  
    IF @FORM=1 THEN
        IF FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09') THEN
        
          SET @SMSTR = @COURSE-1;
          SET @T = 9;
        
        ELSEIF FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27') THEN
        
        
          SET @SMSTR = @COURSE-1;
          SET @T = 10;
        
        ELSEIF FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05') THEN
      
          SET @SMSTR = @COURSE;
          SET @T = 9;
      
        ELSEIF FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23') THEN
      
          SET @SMSTR = @COURSE;
          SET @T = 10;
      
        END IF;

        IF @SMSTR=@COURSE-1 AND @T = 9 THEN
          SET @SRS = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
              AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=1
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09'));
        
          SET @SRSP = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
               AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=2
               AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09'));
               
          SET @L = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
            AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
            AND `type`=3 AND `ltype_id`=3
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09'));
            
         SET @SM = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
             AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
             AND `type`=3 AND `ltype_id`=4
             AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09'));

        ELSEIF @SMSTR=@COURSE-1 AND @T = 10 THEN
          SET @SRS = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
              AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=1
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27'));
        
          SET @SRSP = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
               AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=2
               AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27'));
               
          SET @L = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
            AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
            AND `type`=3 AND `ltype_id`=3
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27'));
            
          SET @SM = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
             AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
             AND `type`=3 AND `ltype_id`=4
             AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27'));
        
        ELSEIF @SMSTR=@COURSE AND @T = 9 THEN
          SET @SRS = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
              AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=1
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05'));
        
          SET @SRSP = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
               AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=2
               AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
                AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05'));
               
          SET @L = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
            AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
            AND `type`=3 AND `ltype_id`=3
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05'));
            
          SET @SM = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
             AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
             AND `type`=3 AND `ltype_id`=4
             AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05'));
        
        ELSEIF @SMSTR=@COURSE AND @T = 10 THEN
          SET @SRS = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
              AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=1
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23'));
        
          SET @SRSP = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
               AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=2
               AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23'));
               
          SET @L = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
            AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
            AND `type`=3 AND `ltype_id`=3
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23'));
            
          SET @SM = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
             AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
             AND `type`=3 AND `ltype_id`=4
             AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23'));
        
        END IF;

        IF @SRS is NOT NULL OR @SRSP is NOT NULL OR @L is NOT NULL OR @SM is NOT NULL THEN
          SET @TK = (IF(@SRS IS NOT NULL,@SRS,0)*0.3)+(IF(@SRSP IS NOT NULL,@SRSP,0)*0.2)+(IF(@L IS NOT NULL,@L,0)*0.1)+(IF(@SM IS NOT NULL,@SM,0)*0.4);
        END IF;
        
        IF @SMSTR is NOT NULL THEN
        SET @CHECK = (SELECT `id` FROM test_started WHERE `ui_id`=new.`ui_id` 
          AND `dis_id`=new.`dis` AND `finished`=1 AND `smstr`=@SMSTR AND `t`=@T);
        END IF;
      
        IF @CHECK is NULL AND @SMSTR is NOT NULL AND @T is NOT NULL AND @TK is NOT NULL THEN
          insert into test_started(ui_id,dis_id,finished,ball,testdate,smstr,t) 
          values (new.`ui_id`,new.`dis`,1,@TK,UNIX_TIMESTAMP(NOW()),@SMSTR,@T);

        ELSEIF @SMSTR is NOT NULL AND @T is NOT NULL AND @TK is NOT NULL THEN
          UPDATE test_started 
          SET ball = @TK,
            testdate = UNIX_TIMESTAMP(NOW())
          WHERE `ui_id`=new.`ui_id` 
            AND `dis_id`=new.`dis` AND `finished`=1 AND `smstr`=@SMSTR AND `t`=@T;
        END IF;
    
    ELSE
      SET @T = 8;
      
      IF FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09') THEN
        
          SET @SMSTR = @COURSE-1;
        
        ELSEIF FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27') THEN
        
          SET @SMSTR = @COURSE-1;
        
      ELSEIF FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05') THEN
      
        SET @SMSTR = @COURSE;
      
      ELSEIF FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23') THEN
      
        SET @SMSTR = @COURSE;
      
        END IF;

        IF @SMSTR=@COURSE-1 AND @T = 8 THEN
          SET @SRS = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
              AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=1
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09'));
        
          SET @SRSP = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
               AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=2
               AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09'));
               
          SET @L = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
            AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
            AND `type`=3 AND `ltype_id`=3
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09'));
            
         SET @SM = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
             AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
             AND `type`=3 AND `ltype_id`=4
             AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09'));

        ELSEIF @SMSTR=@COURSE-1 AND @T = 8 THEN
          SET @SRS = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
              AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=1
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27'));
        
          SET @SRSP = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
               AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=2
               AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27'));
               
          SET @L = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
            AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
            AND `type`=3 AND `ltype_id`=3
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27'));
            
          SET @SM = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
             AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
             AND `type`=3 AND `ltype_id`=4
             AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27'));
        
        ELSEIF @SMSTR=@COURSE AND @T = 8 THEN
          SET @SRS = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
              AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=1
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05'));
        
          SET @SRSP = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
               AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=2
               AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
                AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05'));
               
          SET @L = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
            AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
            AND `type`=3 AND `ltype_id`=3
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05'));
            
          SET @SM = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
             AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
             AND `type`=3 AND `ltype_id`=4
             AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05'));
        
        ELSEIF @SMSTR=@COURSE AND @T = 8 THEN
          SET @SRS = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
              AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=1
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23'));
        
          SET @SRSP = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
               AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=2
               AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23'));
               
          SET @L = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
            AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
            AND `type`=3 AND `ltype_id`=3
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23'));
            
          SET @SM = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
             AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
             AND `type`=3 AND `ltype_id`=4
             AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23'));
        
        END IF;

        IF @SRS is NOT NULL OR @SRSP is NOT NULL OR @L is NOT NULL OR @SM is NOT NULL THEN
          SET @TK = (IF(@SRS IS NOT NULL,@SRS,0)*0.3)+(IF(@SRSP IS NOT NULL,@SRSP,0)*0.2)+(IF(@L IS NOT NULL,@L,0)*0.1)+(IF(@SM IS NOT NULL,@SM,0)*0.4);
        END IF;
        
        IF @SMSTR is NOT NULL THEN
        
        SET @CHECK = (SELECT `id` FROM test_started WHERE `ui_id`=new.`ui_id` 
          AND `dis_id`=new.`dis` AND `finished`=1 AND `smstr`=@SMSTR AND `t`=@T);
          
      END IF;
      
      IF @CHECK is NULL AND @SMSTR is NOT NULL AND @T is NOT NULL AND @TK is NOT NULL THEN
      
        insert into test_started(ui_id,dis_id,finished,ball,testdate,smstr,t) 
        values (new.`ui_id`,new.`dis`,1,@TK,UNIX_TIMESTAMP(NOW()),@SMSTR,@T);

      ELSEIF @SMSTR is NOT NULL AND @T is NOT NULL AND @TK is NOT NULL THEN
      
        UPDATE test_started 
        SET ball = @TK,
            testdate = UNIX_TIMESTAMP(NOW())
      WHERE `ui_id`=new.`ui_id` 
          AND `dis_id`=new.`dis` AND `finished`=1 AND `smstr`=@SMSTR AND `t`=@T;
      
      END IF;
          
    END IF;

  END;

| delimiter ;  

delimiter |

CREATE TRIGGER tk_update AFTER UPDATE ON marks
  FOR EACH ROW
  BEGIN
  
  SET @DATE = (SELECT `date` FROM marks WHERE `id`=new.`id`);
  
  SET @FORM = (SELECT `grup`.`form` FROM `users_info`,`grup` WHERE `users_info`.`grup`=`grup`.`id` 
               AND `users_info`.`id`=new.`ui_id` limit 1);
               
  SET @COURSE = (SELECT `grup`.`course` FROM `users_info`,`grup` WHERE `users_info`.`grup`=`grup`.`id` 
               AND `users_info`.`id`=new.`ui_id` limit 1)*2;
  
    IF @FORM=1 THEN
        IF FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09') THEN
        
          SET @SMSTR = @COURSE-1;
          SET @T = 9;
        
        ELSEIF FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27') THEN
        
        
          SET @SMSTR = @COURSE-1;
          SET @T = 10;
        
        ELSEIF FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05') THEN
      
          SET @SMSTR = @COURSE;
          SET @T = 9;
      
        ELSEIF FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23') THEN
      
          SET @SMSTR = @COURSE;
          SET @T = 10;
      
        END IF;

        IF @SMSTR=@COURSE-1 AND @T = 9 THEN
          SET @SRS = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
              AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=1
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09'));
        
          SET @SRSP = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
               AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=2
               AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09'));
               
          SET @L = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
            AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
            AND `type`=3 AND `ltype_id`=3
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09'));
            
         SET @SM = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
             AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
             AND `type`=3 AND `ltype_id`=4
             AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09'));

        ELSEIF @SMSTR=@COURSE-1 AND @T = 10 THEN
          SET @SRS = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
              AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=1
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27'));
        
          SET @SRSP = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
               AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=2
               AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27'));
               
          SET @L = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
            AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
            AND `type`=3 AND `ltype_id`=3
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27'));
            
          SET @SM = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
             AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
             AND `type`=3 AND `ltype_id`=4
             AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27'));
        
        ELSEIF @SMSTR=@COURSE AND @T = 9 THEN
          SET @SRS = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
              AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=1
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05'));
        
          SET @SRSP = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
               AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=2
               AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
                AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05'));
               
          SET @L = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
            AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
            AND `type`=3 AND `ltype_id`=3
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05'));
            
          SET @SM = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
             AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
             AND `type`=3 AND `ltype_id`=4
             AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05'));
        
        ELSEIF @SMSTR=@COURSE AND @T = 10 THEN
          SET @SRS = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
              AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=1
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23'));
        
          SET @SRSP = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
               AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=2
               AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23'));
               
          SET @L = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
            AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
            AND `type`=3 AND `ltype_id`=3
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23'));
            
          SET @SM = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
             AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
             AND `type`=3 AND `ltype_id`=4
             AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23'));
        
        END IF;

        IF @SRS is NOT NULL OR @SRSP is NOT NULL OR @L is NOT NULL OR @SM is NOT NULL THEN
          SET @TK = (IF(@SRS IS NOT NULL,@SRS,0)*0.3)+(IF(@SRSP IS NOT NULL,@SRSP,0)*0.2)+(IF(@L IS NOT NULL,@L,0)*0.1)+(IF(@SM IS NOT NULL,@SM,0)*0.4);
        END IF;
        
        IF @SMSTR is NOT NULL THEN
        SET @CHECK = (SELECT `id` FROM test_started WHERE `ui_id`=new.`ui_id` 
          AND `dis_id`=new.`dis` AND `finished`=1 AND `smstr`=@SMSTR AND `t`=@T);
        END IF;
      
        IF @CHECK is NULL AND @SMSTR is NOT NULL AND @T is NOT NULL AND @TK is NOT NULL THEN
          insert into test_started(ui_id,dis_id,finished,ball,testdate,smstr,t) 
          values (new.`ui_id`,new.`dis`,1,@TK,UNIX_TIMESTAMP(NOW()),@SMSTR,@T);

        ELSEIF @SMSTR is NOT NULL AND @T is NOT NULL AND @TK is NOT NULL THEN
          UPDATE test_started 
          SET ball = @TK,
            testdate = UNIX_TIMESTAMP(NOW())
          WHERE `ui_id`=new.`ui_id` 
            AND `dis_id`=new.`dis` AND `finished`=1 AND `smstr`=@SMSTR AND `t`=@T;
        END IF;
    
    ELSE
      SET @T = 8;
      
      IF FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09') THEN
        
          SET @SMSTR = @COURSE-1;
        
        ELSEIF FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27') THEN
        
          SET @SMSTR = @COURSE-1;
        
      ELSEIF FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05') THEN
      
        SET @SMSTR = @COURSE;
      
      ELSEIF FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23') THEN
      
        SET @SMSTR = @COURSE;
      
        END IF;

        IF @SMSTR=@COURSE-1 AND @T = 8 THEN
          SET @SRS = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
              AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=1
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09'));
        
          SET @SRSP = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
               AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=2
               AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09'));
               
          SET @L = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
            AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
            AND `type`=3 AND `ltype_id`=3
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09'));
            
         SET @SM = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
             AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
             AND `type`=3 AND `ltype_id`=4
             AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09'));

        ELSEIF @SMSTR=@COURSE-1 AND @T = 8 THEN
          SET @SRS = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
              AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=1
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27'));
        
          SET @SRSP = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
               AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=2
               AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27'));
               
          SET @L = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
            AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
            AND `type`=3 AND `ltype_id`=3
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27'));
            
          SET @SM = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
             AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
             AND `type`=3 AND `ltype_id`=4
             AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27'));
        
        ELSEIF @SMSTR=@COURSE AND @T = 8 THEN
          SET @SRS = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
              AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=1
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05'));
        
          SET @SRSP = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
               AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=2
               AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
                AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05'));
               
          SET @L = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
            AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
            AND `type`=3 AND `ltype_id`=3
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05'));
            
          SET @SM = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
             AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
             AND `type`=3 AND `ltype_id`=4
             AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05'));
        
        ELSEIF @SMSTR=@COURSE AND @T = 8 THEN
          SET @SRS = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
              AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=1
              AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23'));
        
          SET @SRSP = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
               AND `smstr`=new.`smstr` AND `dis`=new.`dis` AND `type`=3 AND `ltype_id`=2
               AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23'));
               
          SET @L = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
            AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
            AND `type`=3 AND `ltype_id`=3
            AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23'));
            
          SET @SM = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=new.`ui_id` 
             AND `smstr`=new.`smstr` AND `dis`=new.`dis` 
             AND `type`=3 AND `ltype_id`=4
             AND FROM_UNIXTIME(@DATE,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(@DATE,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23'));
        
        END IF;

        IF @SRS is NOT NULL OR @SRSP is NOT NULL OR @L is NOT NULL OR @SM is NOT NULL THEN
          SET @TK = (IF(@SRS IS NOT NULL,@SRS,0)*0.3)+(IF(@SRSP IS NOT NULL,@SRSP,0)*0.2)+(IF(@L IS NOT NULL,@L,0)*0.1)+(IF(@SM IS NOT NULL,@SM,0)*0.4);
        END IF;
        
        IF @SMSTR is NOT NULL THEN
        
        SET @CHECK = (SELECT `id` FROM test_started WHERE `ui_id`=new.`ui_id` 
          AND `dis_id`=new.`dis` AND `finished`=1 AND `smstr`=@SMSTR AND `t`=@T);
          
      END IF;
      
      IF @CHECK is NULL AND @SMSTR is NOT NULL AND @T is NOT NULL AND @TK is NOT NULL THEN
      
        insert into test_started(ui_id,dis_id,finished,ball,testdate,smstr,t) 
        values (new.`ui_id`,new.`dis`,1,@TK,UNIX_TIMESTAMP(NOW()),@SMSTR,@T);

      ELSEIF @SMSTR is NOT NULL AND @T is NOT NULL AND @TK is NOT NULL THEN
      
        UPDATE test_started 
        SET ball = @TK,
            testdate = UNIX_TIMESTAMP(NOW())
      WHERE `ui_id`=new.`ui_id` 
          AND `dis_id`=new.`dis` AND `finished`=1 AND `smstr`=@SMSTR AND `t`=@T;
      
      END IF;
          
    END IF;

  END;

| delimiter ;  

delimiter |

CREATE TRIGGER tk_delete AFTER DELETE ON marks
  FOR EACH ROW
  BEGIN
  
  SET @FORM = (SELECT `grup`.`form` FROM `users_info`,`grup` WHERE `users_info`.`grup`=`grup`.`id` 
               AND `users_info`.`id`=old.`ui_id` limit 1);
               
  SET @COURSE = (SELECT `grup`.`course` FROM `users_info`,`grup` WHERE `users_info`.`grup`=`grup`.`id` 
               AND `users_info`.`id`=old.`ui_id` limit 1)*2;
  
    IF @FORM=1 THEN
        IF FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
        AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09') THEN
        
          SET @SMSTR = @COURSE-1;
          SET @T = 9;
        
        ELSEIF FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
        AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27') THEN
        
        
          SET @SMSTR = @COURSE-1;
          SET @T = 10;
        
        ELSEIF FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
        AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05') THEN
      
          SET @SMSTR = @COURSE;
          SET @T = 9;
      
        ELSEIF FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23') THEN
      
          SET @SMSTR = @COURSE;
          SET @T = 10;
      
        END IF;

        IF @SMSTR=@COURSE-1 AND @T = 9 THEN
          SET @SRS = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
              AND `smstr`=old.`smstr` AND `dis`=old.`dis` AND `type`=3 AND `ltype_id`=1
              AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
              AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09'));
        
          SET @SRSP = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
               AND `smstr`=old.`smstr` AND `dis`=old.`dis` AND `type`=3 AND `ltype_id`=2
               AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
              AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09'));
               
          SET @L = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
            AND `smstr`=old.`smstr` AND `dis`=old.`dis` 
            AND `type`=3 AND `ltype_id`=3
            AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
            AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09'));
            
         SET @SM = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
             AND `smstr`=old.`smstr` AND `dis`=old.`dis` 
             AND `type`=3 AND `ltype_id`=4
             AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
            AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09'));

        ELSEIF @SMSTR=@COURSE-1 AND @T = 10 THEN
          SET @SRS = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
              AND `smstr`=old.`smstr` AND `dis`=old.`dis` AND `type`=3 AND `ltype_id`=1
              AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
              AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27'));
        
          SET @SRSP = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
               AND `smstr`=old.`smstr` AND `dis`=old.`dis` AND `type`=3 AND `ltype_id`=2
               AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
              AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27'));
               
          SET @L = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
            AND `smstr`=old.`smstr` AND `dis`=old.`dis` 
            AND `type`=3 AND `ltype_id`=3
            AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
            AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27'));
            
          SET @SM = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
             AND `smstr`=old.`smstr` AND `dis`=old.`dis` 
             AND `type`=3 AND `ltype_id`=4
             AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
            AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27'));
        
        ELSEIF @SMSTR=@COURSE AND @T = 9 THEN
          SET @SRS = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
              AND `smstr`=old.`smstr` AND `dis`=old.`dis` AND `type`=3 AND `ltype_id`=1
              AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
              AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05'));
        
          SET @SRSP = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
               AND `smstr`=old.`smstr` AND `dis`=old.`dis` AND `type`=3 AND `ltype_id`=2
               AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
                AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05'));
               
          SET @L = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
            AND `smstr`=old.`smstr` AND `dis`=old.`dis` 
            AND `type`=3 AND `ltype_id`=3
            AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
            AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05'));
            
          SET @SM = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
             AND `smstr`=old.`smstr` AND `dis`=old.`dis` 
             AND `type`=3 AND `ltype_id`=4
             AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
            AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05'));
        
        ELSEIF @SMSTR=@COURSE AND @T = 10 THEN
          SET @SRS = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
              AND `smstr`=old.`smstr` AND `dis`=old.`dis` AND `type`=3 AND `ltype_id`=1
              AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23'));
        
          SET @SRSP = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
               AND `smstr`=old.`smstr` AND `dis`=old.`dis` AND `type`=3 AND `ltype_id`=2
               AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23'));
               
          SET @L = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
            AND `smstr`=old.`smstr` AND `dis`=old.`dis` 
            AND `type`=3 AND `ltype_id`=3
            AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23'));
            
          SET @SM = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
             AND `smstr`=old.`smstr` AND `dis`=old.`dis` 
             AND `type`=3 AND `ltype_id`=4
             AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23'));
        
        END IF;

        IF @SRS is NOT NULL OR @SRSP is NOT NULL OR @L is NOT NULL OR @SM is NOT NULL THEN
          SET @TK = (IF(@SRS IS NOT NULL,@SRS,0)*0.3)+(IF(@SRSP IS NOT NULL,@SRSP,0)*0.2)+(IF(@L IS NOT NULL,@L,0)*0.1)+(IF(@SM IS NOT NULL,@SM,0)*0.4);
        END IF;
        
        IF @SMSTR is NOT NULL THEN
        SET @CHECK = (SELECT `id` FROM test_started WHERE `ui_id`=old.`ui_id` 
          AND `dis_id`=old.`dis` AND `finished`=1 AND `smstr`=@SMSTR AND `t`=@T);
        END IF;
      
        IF @CHECK is NULL AND @SMSTR is NOT NULL AND @T is NOT NULL AND @TK is NOT NULL THEN
          insert into test_started(ui_id,dis_id,finished,ball,testdate,smstr,t) 
          values (old.`ui_id`,old.`dis`,1,@TK,UNIX_TIMESTAMP(NOW()),@SMSTR,@T);

        ELSEIF @SMSTR is NOT NULL AND @T is NOT NULL AND @TK is NOT NULL THEN
          UPDATE test_started 
          SET ball = @TK,
            testdate = UNIX_TIMESTAMP(NOW())
          WHERE `ui_id`=old.`ui_id` 
            AND `dis_id`=old.`dis` AND `finished`=1 AND `smstr`=@SMSTR AND `t`=@T;
        END IF;
    
    ELSE
      SET @T = 8;
      
      IF FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
        AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09') THEN
        
          SET @SMSTR = @COURSE-1;
        
        ELSEIF FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
        AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27') THEN
        
          SET @SMSTR = @COURSE-1;
        
      ELSEIF FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
        AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05') THEN
      
        SET @SMSTR = @COURSE;
      
      ELSEIF FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23') THEN
      
        SET @SMSTR = @COURSE;
      
        END IF;

        IF @SMSTR=@COURSE-1 AND @T = 8 THEN
          SET @SRS = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
              AND `smstr`=old.`smstr` AND `dis`=old.`dis` AND `type`=3 AND `ltype_id`=1
              AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
              AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09'));
        
          SET @SRSP = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
               AND `smstr`=old.`smstr` AND `dis`=old.`dis` AND `type`=3 AND `ltype_id`=2
               AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
              AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09'));
               
          SET @L = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
            AND `smstr`=old.`smstr` AND `dis`=old.`dis` 
            AND `type`=3 AND `ltype_id`=3
            AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
            AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09'));
            
         SET @SM = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
             AND `smstr`=old.`smstr` AND `dis`=old.`dis` 
             AND `type`=3 AND `ltype_id`=4
             AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','09 01') 
            AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','10 09'));

        ELSEIF @SMSTR=@COURSE-1 AND @T = 8 THEN
          SET @SRS = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
              AND `smstr`=old.`smstr` AND `dis`=old.`dis` AND `type`=3 AND `ltype_id`=1
              AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
              AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27'));
        
          SET @SRSP = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
               AND `smstr`=old.`smstr` AND `dis`=old.`dis` AND `type`=3 AND `ltype_id`=2
               AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
              AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27'));
               
          SET @L = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
            AND `smstr`=old.`smstr` AND `dis`=old.`dis` 
            AND `type`=3 AND `ltype_id`=3
            AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
            AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27'));
            
          SET @SM = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
             AND `smstr`=old.`smstr` AND `dis`=old.`dis` 
             AND `type`=3 AND `ltype_id`=4
             AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','10 18') 
            AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','11 27'));
        
        ELSEIF @SMSTR=@COURSE AND @T = 8 THEN
          SET @SRS = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
              AND `smstr`=old.`smstr` AND `dis`=old.`dis` AND `type`=3 AND `ltype_id`=1
              AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
              AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05'));
        
          SET @SRSP = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
               AND `smstr`=old.`smstr` AND `dis`=old.`dis` AND `type`=3 AND `ltype_id`=2
               AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
                AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05'));
               
          SET @L = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
            AND `smstr`=old.`smstr` AND `dis`=old.`dis` 
            AND `type`=3 AND `ltype_id`=3
            AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
            AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05'));
            
          SET @SM = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
             AND `smstr`=old.`smstr` AND `dis`=old.`dis` 
             AND `type`=3 AND `ltype_id`=4
             AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','01 24') 
            AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','03 05'));
        
        ELSEIF @SMSTR=@COURSE AND @T = 8 THEN
          SET @SRS = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
              AND `smstr`=old.`smstr` AND `dis`=old.`dis` AND `type`=3 AND `ltype_id`=1
              AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23'));
        
          SET @SRSP = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
               AND `smstr`=old.`smstr` AND `dis`=old.`dis` AND `type`=3 AND `ltype_id`=2
               AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23'));
               
          SET @L = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
            AND `smstr`=old.`smstr` AND `dis`=old.`dis` 
            AND `type`=3 AND `ltype_id`=3
            AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23'));
            
          SET @SM = (SELECT SUM(value)/COUNT(value) FROM marks WHERE `ui_id`=old.`ui_id` 
             AND `smstr`=old.`smstr` AND `dis`=old.`dis` 
             AND `type`=3 AND `ltype_id`=4
             AND FROM_UNIXTIME(old.`date`,'%Y %m %d')>=concat(YEAR(CURRENT_DATE()),' ','03 14') 
        AND FROM_UNIXTIME(old.`date`,'%Y %m %d')<=concat(YEAR(CURRENT_DATE()),' ','04 23'));
        
        END IF;

        IF @SRS is NOT NULL OR @SRSP is NOT NULL OR @L is NOT NULL OR @SM is NOT NULL THEN
          SET @TK = (IF(@SRS IS NOT NULL,@SRS,0)*0.3)+(IF(@SRSP IS NOT NULL,@SRSP,0)*0.2)+(IF(@L IS NOT NULL,@L,0)*0.1)+(IF(@SM IS NOT NULL,@SM,0)*0.4);
        END IF;
        
        IF @SMSTR is NOT NULL THEN
        
        SET @CHECK = (SELECT `id` FROM test_started WHERE `ui_id`=old.`ui_id` 
          AND `dis_id`=old.`dis` AND `finished`=1 AND `smstr`=@SMSTR AND `t`=@T);
          
      END IF;
      
      IF @CHECK is NULL AND @SMSTR is NOT NULL AND @T is NOT NULL AND @TK is NOT NULL THEN
      
        insert into test_started(ui_id,dis_id,finished,ball,testdate,smstr,t) 
        values (old.`ui_id`,old.`dis`,1,@TK,UNIX_TIMESTAMP(NOW()),@SMSTR,@T);

      ELSEIF @SMSTR is NOT NULL AND @T is NOT NULL AND @TK is NOT NULL THEN
      
        UPDATE test_started 
        SET ball = @TK,
            testdate = UNIX_TIMESTAMP(NOW())
      WHERE `ui_id`=old.`ui_id` 
          AND `dis_id`=old.`dis` AND `finished`=1 AND `smstr`=@SMSTR AND `t`=@T;
      
      END IF;
          
    END IF;

  END;

| delimiter ;  

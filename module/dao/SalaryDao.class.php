<?php
/**
 * 工资dao
 * @author zhang.chao
 *
 */
class SalaryDao extends BaseDao {
	
	/**
	 *
	 * @return BaseConfigDao
	 */
	function SalaryDao() {
		parent::BaseDao ();
	}
	function getAdminList() {
		$sql = "select *  from  DM_Admin  where  del_flag=0";
		$result = $this->g_db_query ( $sql );
		return $result;
	}
	function getAllComByComId() {
		$sql = "select *  from  OA_company  where  check=0";
		$result = $this->g_db_query ( $sql );
		return $result;
	}
	function saveSalaryTime($salaryTime) {
		$sql = "insert into OA_salarytime (companyId,salaryTime,op_salaryTime,mark) values({$salaryTime['companyId']}
    	,'{$salaryTime['salaryTime']}','{$salaryTime['op_salaryTime']}','{$salaryTime['mark']}');";
		$list = $this->g_db_query ( $sql );
		if ($list) {
			return $this->g_db_last_insert_id ();
		} else {
			return false;
		}
	}
	function saveSalaryNianTime($salaryTime) {
		$sql = "insert into OA_salarytime_other (companyId,salaryTime,op_salaryTime,salaryType) values({$salaryTime['companyId']}
    	,'{$salaryTime['salary_time']}','{$salaryTime['op_salaryTime']}',{$salaryTime['salaryType']});";
		$list = $this->g_db_query ( $sql );
		if ($list) {
			return $this->g_db_last_insert_id ();
		} else {
			return false;
		}
	}
	function saveSalary($salary) {
		$sql = "insert  into  OA_salary (employid,salaryTimeId,per_yingfaheji,per_shiye,per_yiliao,per_yanglao,per_gongjijin,per_daikoushui
    	,per_koukuangheji,per_shifaheji,com_shiye,com_yiliao,com_yanglao,com_gongshang,com_shengyu,com_gongjijin,com_heji,
    	laowufei,canbaojin,danganfei,paysum_zhongqi
    	) values('{$salary['employid']}',{$salary['salaryTimeId']},{$salary['per_yingfaheji']},
    	{$salary['per_shiye']},{$salary['per_yiliao']},{$salary['per_yanglao']},{$salary['per_gongjijin']},
    	{$salary['per_daikoushui']},{$salary['per_koukuangheji']},{$salary['per_shifaheji']},{$salary['com_shiye']},{$salary['com_yiliao']},
    	{$salary['com_yanglao']},{$salary['com_gongshang']},{$salary['com_shengyu']},{$salary['com_gongjijin']},
    	{$salary['com_heji']},{$salary['laowufei']},{$salary['canbaojin']},{$salary['danganfei']},{$salary['paysum_zhongqi']});";
		$list = $this->g_db_query ( $sql );
		if ($list) {
			return $this->g_db_last_insert_id ();
		} else {
			return false;
		}
	}
	// 保存年终奖工资
	function saveNianSalary($salary) {
		$sql = "insert  into  OA_nian_salary (employid,salaryTimeId,nianzhongjiang,nian_daikoushui,yingfaheji,shifajinka,jiaozhongqi)
    	values('{$salary['employid']}',{$salary['salaryTimeId']},{$salary['nianzhongjiang']},
    	{$salary['nian_daikoushui']},{$salary['yingfaheji']},{$salary['shifajinka']},{$salary['jiaozhongqi']});";
		$list = $this->g_db_query ( $sql );
		if ($list) {
			return $this->g_db_last_insert_id ();
		} else {
			return false;
		}
	}
	// 保存二次工资
	function saveErSalary($salary) {
		$sql = "insert  into  OA_er_salary (employid,salaryTimeId,dangyueyingfa,ercigongziheji,yingfaheji,shiye,yiliao,yanglao
    	,gongjijin,yingkoushui,yikoushui,bukoushui,jinka,jiaozhongqi) 
    	values('{$salary['employid']}',{$salary['salaryTimeId']},{$salary['dangyueyingfa']},
    	{$salary['ercigongziheji']},{$salary['yingfaheji']},{$salary['shiye']},{$salary['yiliao']},
    	{$salary['yanglao']},{$salary['gongjijin']},{$salary['yingkoushui']},{$salary['yikoushui']},
    	{$salary['bukoushui']},{$salary['jinka']},{$salary['jiaozhongqi']});";
		$list = $this->g_db_query ( $sql );
		if ($list) {
			return $this->g_db_last_insert_id ();
		} else {
			return false;
		}
	}
	function getErSalaryByDateNo($date, $eno) {
		$sql = " select  *  from  OA_er_salary  ,OA_salarytime_other  where OA_salarytime_other.id=OA_er_salary.salaryTimeId and  OA_salarytime_other.salaryTime='" . $date . "' and OA_er_salary.employid='" . $eno . "' ";
		$result = $this->g_db_query ( $sql );
		return mysql_fetch_array ( $result );
	}
	// 存储工资动态字段
	function saveSalaryMovement($salaryMovement) {
		/**
		 * OA_salarymovement` (
		 * `id` int(11) NOT NULL AUTO_INCREMENT,
		 * `fieldName` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
		 * `fieldIndex` int(11) DEFAULT NULL,
		 * `fieldValue` float(10,0) NOT NULL,
		 * `salaryId` int(11) NOT NULL,
		 * PRIMARY KEY (`id`)
		 *
		 * @var unknown_type
		 */
		$sql = "insert into OA_salarymovement  (fieldName,salaryId,fieldValue)  values('{$salaryMovement['fieldName']}',{$salaryMovement['salaryId']},'{$salaryMovement['fieldValue']}');";
		$list = $this->g_db_query ( $sql );
		if ($list) {
			return $this->g_db_last_insert_id ();
		} else {
			return false;
		}
	}
	// 存储二次工资动态字段
	function saveErSalaryMovement($salaryMovement) {
		/**
		 * o`id` int(11) NOT NULL AUTO_INCREMENT,
		 * `fieldName` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
		 * `fieldIndex` int(11) DEFAULT NULL,
		 * `fieldValue` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
		 * `ersalaryId` int(11) DEFAULT NULL,
		 *
		 * @var unknown_type
		 */
		$sql = "insert into OA_er_movement  (fieldName,ersalaryId,fieldValue)  values('{$salaryMovement['fieldName']}',{$salaryMovement['ersalaryId']},'{$salaryMovement['fieldValue']}');";
		$list = $this->g_db_query ( $sql );
		if ($list) {
			return $this->g_db_last_insert_id ();
		} else {
			return false;
		}
	}
	// 查询员工个人工资根据员工相应字段BY孙瑞鹏
	function searchSalaryListBy_Salary($id) {
		$sql = "select st.*,st.id  as stId,e.* ,e.id as  eId,s.*,s.id as sId  from OA_salarytime st,OA_employ e,OA_salary s
		where  st.id=s.salaryTimeId  and e.e_num=s.employid and s.employid='{$id}' ";
		$result = $this->g_db_query ( $sql );
		return $result;
	}
	
	// 保存工资合计项
	function saveSumSalary($salary) {
		$sql = "insert  into  OA_total (salaryTime_Id,sum_per_yingfaheji,sum_per_shiye,sum_per_yiliao,sum_per_yanglao,sum_per_gongjijin,sum_per_daikoushui
    	,sum_per_koukuangheji,sum_per_shifaheji,sum_com_shiye,sum_com_yiliao,sum_com_yanglao,sum_com_gongshang,sum_com_shengyu,sum_com_gongjijin,sum_com_heji,
    	sum_laowufei,sum_canbaojin,sum_danganfei,sum_paysum_zhongqi
    	) values({$salary['salaryTimeId']},{$salary['per_yingfaheji']},
    	{$salary['per_shiye']},{$salary['per_yiliao']},{$salary['per_yanglao']},{$salary['per_gongjijin']},
    	{$salary['per_daikoushui']},{$salary['per_koukuangheji']},{$salary['per_shifaheji']},{$salary['com_shiye']},{$salary['com_yiliao']},
    	{$salary['com_yanglao']},{$salary['com_gongshang']},{$salary['com_shengyu']},{$salary['com_gongjijin']},
    	{$salary['com_heji']},{$salary['laowufei']},{$salary['canbaojin']},{$salary['danganfei']},{$salary['paysum_zhongqi']});";
		$list = $this->g_db_query ( $sql );
		if ($list) {
			return $this->g_db_last_insert_id ();
		} else {
			return false;
		}
	}
	// 保存年终奖合计项
	function saveSumNianSalary($salary) {
		$sql = "insert  into  OA_nian_total (salaryTime_Id,sum_nianzhongjiang,sum_daikoushui,sum_yingfaheji,sum_shifajika,sum_jiaozhongqi)
        values({$salary['salaryTimeId']},{$salary['nianzhongjiang']},{$salary['yingfaheji']},{$salary['nian_daikoushui']},{$salary['shifajinka']},{$salary['jiaozhongqi']});";
		$list = $this->g_db_query ( $sql );
		if ($list) {
			return $this->g_db_last_insert_id ();
		} else {
			return false;
		}
	}
	// 保存二次工资合计项
	function saveSumErSalary($salary) {
		$sql = "insert  into  OA_er_total (salaryTime_Id,sum_dangyueyingfa,sum_ercigongziheji,sum_yingfaheji,sum_shiye
        ,sum_yiliao,sum_yanglao,sum_gongjijin,sum_yingkoushui,sum_yikoushui,sum_bukoushui,sum_jinka,sum_jiaozhongqi) 
        values({$salary['salaryTimeId']},{$salary['dangyueyingfa']},{$salary['ercigongziheji']},{$salary['yingfaheji']},
        {$salary['shiye']},{$salary['yiliao']},{$salary['yanglao']},{$salary['gongjijin']},{$salary['yingkoushui']},
        {$salary['yikoushui']},{$salary['bukoushui']},{$salary['jinka']},{$salary['jiaozhongqi']});";
		$list = $this->g_db_query ( $sql );
		if ($list) {
			return $this->g_db_last_insert_id ();
		} else {
			return false;
		}
	}
	function searhSalaryTimeListByComIdAndDate($date, $comid) {
		$sql = "select * from OA_salarytime  where  salaryTime='{$date}' and companyId=$comid ";
		$result = $this->g_db_query ( $sql );
		return mysql_fetch_array ( $result );
	}
	function searhNianSalaryTimeListByComIdAndDate($date, $comid) {
		$sql = "select * from OA_salarytime_other  where  salaryTime like'%{$date}%' and companyId=$comid ";
		$result = $this->g_db_query ( $sql );
		return mysql_fetch_array ( $result );
	}
	function searhSalaryTimeList($where = null) {
		$sql = "select st.*,c.company_name from OA_salarytime st,OA_company c where  st.companyId=c.id  ";
		if ($where != null) {
			if ($where ['companyName'] != "") {
				$sql .= " and c.company_name like '%{$where['companyName']}%' ";
			}
			if ($where ['salaryTime'] != "") {
				$sql .= " and st.salaryTime='{$where['salaryTime']}' ";
			}
			if ($where ['op_salaryTime'] != "") {
				$sql .= " and st.op_salaryTime='{$where['op_salaryTime']}' ";
			}
		}
		$sql .= " order by op_salaryTime desc ";
		$list = $this->g_db_query ( $sql );
		return $list;
	}

    // 发票统计数BY孙瑞鹏
    function searhFapiaoCount($where = null) {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "select count(*) as cnt  from OA_bill b ,OA_salarytime  s ,OA_company c ,OA_admin_company a
                    WHERE  b.salaryTime_id = s.id
                    AND s.companyId=c.id
                    AND a.companyId = c.id
                    AND bill_type = 1
                    AND a.adminId = $id";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and company_name like '%{$where['companyName']}%' ";
            }
            if ($where ['salaryTime'] != "") {
                $sql .= " and salaryTime='{$where['salaryTime']}' ";
            }
        }
        $result = $this->g_db_query ( $sql );
        if (! $result) {
            return 0;
        }
        $row = mysql_fetch_assoc ( $result );
        return $row ['cnt'];
    }

    // 到账统计数BY孙瑞鹏
    function searhDaozhangCount($where = null) {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "select count(*) as cnt  from OA_bill b ,OA_salarytime  s ,OA_company c ,OA_admin_company a
                    WHERE  b.salaryTime_id = s.id
                    AND s.companyId=c.id
                    AND a.companyId = c.id
                    AND bill_type = 3
                    AND a.adminId = $id";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and company_name like '%{$where['companyName']}%' ";
            }
            if ($where ['salaryTime'] != "") {
                $sql .= " and salaryTime='{$where['salaryTime']}' ";
            }
        }
        $result = $this->g_db_query ( $sql );
        if (! $result) {
            return 0;
        }
        $row = mysql_fetch_assoc ( $result );
        return $row ['cnt'];
    }

	// 个税统计BY孙瑞鹏
	function searhSalaryTimeCount($where = null) {
        $id = $_SESSION ['admin'] ['id'];
		$sql = "select count(*) as cnt  from (SELECT emp.id  ,t.salaryTime  ,e_company company_name
			FROM OA_salary s ,OA_employ emp,OA_salarytime t
			WHERE s.employid = emp.e_num AND s.salaryTimeId = t.id
			 AND convert( emp.e_company  using utf8) IN (
            SELECT  company_name  FROM OA_company c ,OA_admin_company a
           WHERE   c.id = a.companyId AND a.adminId = $id
             )
			GROUP BY e_company,t.salaryTime) m
            where 1=1";
		if ($where != null) {
			if ($where ['companyName'] != "") {
				$sql .= " and m.company_name like '%{$where['companyName']}%' ";
			}
			if ($where ['salaryTime'] != "") {
				$sql .= " and m.salaryTime='{$where['salaryTime']}' ";
			}
		}
		$result = $this->g_db_query ( $sql );
		if (! $result) {
			return 0;
		}
		$row = mysql_fetch_assoc ( $result );
		return $row ['cnt'];
	}
	
	// 个税类型BY孙瑞鹏
	function searhSalaryTypeCount($where = null) {
        $id = $_SESSION ['admin'] ['id'];
		$sql = "select count(*) as cnt   from OA_company c,OA_admin_company a  where 1=1
  and a.companyId = c.id  and  a.adminId = $id";
		if ($where != null) {
			if ($where ['companyName'] != "") {
				$sql .= " and company_name like '%{$where['companyName']}%' ";
			}
			if ($where ['salaryTime'] != "") {
				$sql .= " and geshui_dateType='{$where['salaryTime']}' ";
			}
		}
		$result = $this->g_db_query ( $sql );
		if (! $result) {
			return 0;
		}
		$row = mysql_fetch_assoc ( $result );
		return $row ['cnt'];
	}

    /**
     * 工资查询 dao
     * @param null $where
     * @return int
     */
    function searhSalaryTimeListCount($where = null) {
        $id = $_SESSION ['admin'] ['id'];
		$sql = "select count(*) as cnt  from OA_salarytime st,OA_company c,OA_admin_company a where  a.adminId=$id and a.companyId = c.id and st.companyId=c.id  ";
		if ($where != null) {
			if ($where ['companyName'] != "") {
				$sql .= " and c.company_name like '%{$where['companyName']}%' ";
			}
			if ($where ['salaryTime'] != "") {
				$sql .= " and st.salaryTime like '%{$where['salaryTime']}%' ";
			}
			if ($where ['op_salaryTime'] != "") {
				$sql .= " and st.op_salaryTime>='{$where['op_time']}' and st.op_salaryTime<'{$where['op_salaryTime']}' ";
			}
		}
		$result = $this->g_db_query ( $sql );
		if (! $result) {
			return 0;
		}
		$row = mysql_fetch_assoc ( $result );
		return $row ['cnt'];
	}
	function searhSalaryTimeListPage($start = NULL, $limit = NULL, $sort = NULL, $where = null) {
        $id = $_SESSION ['admin'] ['id'];
		$sql = "select st.*,c.company_name from OA_salarytime st,OA_company c,OA_admin_company a where a.adminId=$id and a.companyId = c.id and st.companyId=c.id  ";
		if ($where != null) {
			if ($where ['companyName'] != "") {
				$sql .= " and c.company_name like '%{$where['companyName']}%' ";
			}
			if ($where ['salaryTime'] != "") {
				$sql .= " and st.salaryTime  like '%{$where['salaryTime']}%' ";
			}
			if ($where ['op_salaryTime'] != "") {
				$sql .= " and st.op_salaryTime>='{$where['op_time']}' and st.op_salaryTime<'{$where['op_salaryTime']}'";
			}
		}
		if ($sort) {
			$sql .= " order by $sort";
		}
		if ($start >= 0 && $limit) {
			$sql .= " limit $start,$limit";
		}
		// $sql.=" order by op_salaryTime desc ";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	// 计算个税合计BY孙瑞鹏
	function searhGeshuiListPage($start = NULL, $limit = NULL, $sort = NULL, $where = null) {
        $id = $_SESSION ['admin'] ['id'];
		$sql = "SELECT yi.id company_id,yi.salaryTime,yi.e_company company_name,yi.su daikou,er.su bukou, nian.su nian,(yi.su+IFNULL(er.su,0)+IFNULL(nian.su,0)) geshuiSum FROM
			(
			SELECT emp.id,  t.salaryTime  ,e_company,IFNULL(SUM(s.per_daikoushui),0) su
			FROM OA_salary s ,OA_employ emp,OA_salarytime t
			WHERE s.employid = emp.e_num AND s.salaryTimeId = t.id
			AND convert( emp.e_company  using utf8) IN (
            SELECT  company_name  FROM OA_company c ,OA_admin_company a
            WHERE   c.id = a.companyId AND a.adminId = $id
             )
			GROUP BY e_company,t.salaryTime
			) yi
			LEFT JOIN
			 (
			SELECT  t.salaryTime,e_company,IFNULL(SUM(e.bukoushui),0) su
			FROM OA_er_salary e ,OA_employ emp,OA_salarytime_other t
			WHERE e.employid = emp.e_num  AND e.salaryTimeId = t.id
			GROUP BY e_company,t.salaryTime
			) er
			ON yi.e_company = er.e_company AND yi.salaryTime = er.salaryTime
			LEFT JOIN
			(
			SELECT   t.salaryTime,e_company,IFNULL(SUM(n.nian_daikoushui),0) su
			FROM OA_nian_salary n  ,OA_employ emp,OA_salarytime_other t
			WHERE n.employid = emp.e_num AND n.salaryTimeId = t.id
			GROUP BY e_company, t.salaryTime
			) nian
			ON  yi.e_company = nian.e_company AND yi.salaryTime = nian.salaryTime
    		where 1=1
			";
		if ($where != null) {
			if ($where ['companyName'] != "") {
				$sql .= " and yi.e_company like '%{$where['companyName']}%' ";
			}
			if ($where ['salaryTime'] != "") {
				$time = date ( "Ymd", strtotime ( "last month", strtotime ( $where ['salaryTime'] ) ) );
				$sql .= "and (
    			(yi.salaryTime like '%{$where['salaryTime']}%'  AND convert(yi.e_company using utf8) IN (SELECT company_name FROM OA_company WHERE geshui_dateType = 1))
    			OR
    			(yi.salaryTime like '%{$time}%'  AND convert(yi.e_company using utf8) IN (SELECT company_name FROM OA_company WHERE geshui_dateType = 2))
    			)";
			}
		}
		if ($sort) {
			$sql .= " order by $sort";
		}
		if ($start >= 0 && $limit) {
			$sql .= " limit $start,$limit";
		}
		// $sql.=" order by op_salaryTime desc ";
		$list = $this->g_db_query ( $sql );
		return $list;
	}

    // 发票BY孙瑞鹏
    function searhFapiaoListPage($start = NULL, $limit = NULL, $sort = NULL, $where = null) {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "SELECT  bill_no,salaryTime ,company_name ,bill_value FROM OA_bill b ,OA_salarytime  s ,OA_company c ,OA_admin_company a
                    WHERE  b.salaryTime_id = s.id
                    AND s.companyId=c.id
                    AND a.companyId = c.id
                    AND bill_type = 1
                    AND a.adminId = $id";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and company_name like '%{$where['companyName']}%' ";
            }
            if ($where ['salaryTime'] != "") {
                $sql .= "  and salaryTime like '%{$where['salaryTime']}%' ";
            }

        }
        if ($sort) {
            $sql .= " order by $sort";
        }
        if ($start >= 0 && $limit) {
            $sql .= " limit $start,$limit";
        }
        $list = $this->g_db_query ( $sql );
        return $list;
    }

    // 到账BY孙瑞鹏
    function searhDaozhangListPage($start = NULL, $limit = NULL, $sort = NULL, $where = null) {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "SELECT  salaryTime daozhangTime ,company_name cname ,bill_value  daozhangValue FROM OA_bill b ,OA_salarytime  s ,OA_company c ,OA_admin_company a
                    WHERE  b.salaryTime_id = s.id
                    AND s.companyId=c.id
                    AND a.companyId = c.id
                    AND bill_type = 3
                    AND a.adminId = $id";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and company_name like '%{$where['companyName']}%' ";
            }
            if ($where ['salaryTime'] != "") {
                $sql .= "  and salaryTime like '%{$where['salaryTime']}%' ";
            }

        }
        if ($sort) {
            $sql .= " order by $sort";
        }
        if ($start >= 0 && $limit) {
            $sql .= " limit $start,$limit";
        }
        $list = $this->g_db_query ( $sql );
        return $list;
    }
	
	// 个税类型BY孙瑞鹏
	function searhGeshuiTypePage($start = NULL, $limit = NULL, $sort = NULL, $where = null) {
        $id = $_SESSION ['admin'] ['id'];
		$sql = "SELECT c.id,c.company_name,c.geshui_dateType  from OA_company c,OA_admin_company a  where 1=1
  and a.companyId = c.id  and  a.adminId = $id";
		if ($where != null) {
			if ($where ['companyName'] != "") {
				$sql .= " and company_name like '%{$where['companyName']}%' ";
			}
			if ($where ['salaryTime'] != "") {
				$sql .= " and geshui_dateType='{$where['salaryTime']}' ";
			}
		}
		if ($sort) {
			$sql .= " order by $sort";
		}
		if ($start >= 0 && $limit) {
			$sql .= " limit $start,$limit";
		}
		// $sql.=" order by op_salaryTime desc ";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	function searhErSalaryTimeListCount($where = null) {
        $id = $_SESSION ['admin'] ['id'];
		$sql = "select count(*) as cnt  from OA_salarytime_other st,OA_company c,OA_admin_company a where  a.adminId=$id and a.companyId = c.id and st.companyId=c.id   and salaryType=" . ER_SALARY_TIME_TYPE;
		if ($where != null) {
			if ($where ['companyName'] != "") {
				$sql .= " and c.company_name like '%{$where['companyName']}%' ";
			}
			if ($where ['salaryTime'] != "") {
				$sql .= " and st.salaryTime like '%{$where['salaryTime']}%' ";
			}
			if ($where ['op_salaryTime'] != "") {
				$sql .= " and st.op_salaryTime='{$where['op_salaryTime']}' ";
			}
		}
		$result = $this->g_db_query ( $sql );
		if (! $result) {
			return 0;
		}
		$row = mysql_fetch_assoc ( $result );
		return $row ['cnt'];
	}
	function searhErSalaryTimeListPage($start = NULL, $limit = NULL, $sort = NULL, $where = null) {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "select st.*,c.company_name from OA_salarytime_other st,OA_company c,OA_admin_company a where  a.adminId=$id and a.companyId = c.id and st.companyId=c.id  and salaryType=" . ER_SALARY_TIME_TYPE;
		if ($where != null) {
			if ($where ['companyName'] != "") {
				$sql .= " and c.company_name like '%{$where['companyName']}%' ";
			}
			if ($where ['salaryTime'] != "") {
				$sql .= " and st.salaryTime   like '%{$where['salaryTime']}%'  ";
			}
			if ($where ['op_salaryTime'] != "") {
				$sql .= " and st.op_salaryTime='{$where['op_salaryTime']}' ";
			}
		}
		if ($sort) {
			$sql .= " order by $sort";
		}
		if ($start >= 0 && $limit) {
			$sql .= " limit $start,$limit";
		}
		// $sql.=" order by op_salaryTime desc ";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	// 个税详细BY孙瑞鹏
	function searchGeshuiBy_SalaryTimeId($sid, $stime) {
		$sql = "SELECT yi.id company_id,yi.e_name ename ,e_num,yi.salaryTime,yi.e_company companyname,yi.su daikou,er.su bukou,nian.su nian,(yi.su+IFNULL(er.su,0)+IFNULL(nian.su,0)) geshuiSum FROM
    	(
    	SELECT emp.id,e_num,emp.e_name,  t.salaryTime  ,e_company,IFNULL(SUM(s.per_daikoushui),0) su
    	FROM OA_salary s ,OA_employ emp,OA_salarytime t
    	WHERE s.employid = emp.e_num AND s.salaryTimeId = t.id
    	AND e_company = '$sid'
    	AND t.salaryTime = '$stime'
    	GROUP BY s.employid,t.salaryTime
    	ORDER BY e_name
    	) yi
    	LEFT JOIN
    	(
    	SELECT emp.e_name, t.salaryTime,e_company,IFNULL(SUM(e.bukoushui),0) su
    	FROM OA_er_salary e ,OA_employ emp,OA_salarytime_other t
    	WHERE e.employid = emp.e_num  AND e.salaryTimeId = t.id
    	AND e_company = '$sid'
    	AND t.salaryTime = '$stime'
    	GROUP BY e.employid,t.salaryTime
    	) er
    	ON yi.e_name = er.e_name AND yi.salaryTime = er.salaryTime
    	LEFT JOIN
    	(
    	SELECT  emp.e_name,   t.salaryTime,e_company,IFNULL(SUM(n.nian_daikoushui),0) su
    	FROM OA_nian_salary n  ,OA_employ emp,OA_salarytime_other t
    	WHERE n.employid = emp.e_num AND n.salaryTimeId = t.id
    	AND e_company = '$sid'
    	AND t.salaryTime = '$stime'
    	GROUP BY n.employid,t.salaryTime
    	) nian
    	ON  yi.e_name = nian.e_name AND yi.salaryTime = nian.salaryTime
    	where 1=1";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	
	// 个税类型设置BY孙瑞鹏
	function searchGeshuiBy_SalaryTypeId($sid) {
		$sql = "SELECT geshui_dateType ,company_name FROM OA_company WHERE id=$sid";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	
	// 个税类型设置本月BY孙瑞鹏
	function setTypeBenyue($sid) {
		$sql = "UPDATE OA_company SET geshui_dateType = 1 WHERE id = $sid";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	
	// 个税类型设置上月BY孙瑞鹏
	function setTypeShangyue($sid) {
		$sql = "UPDATE OA_company SET geshui_dateType = 2 WHERE id = $sid";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	// 获得所有年终奖
	function searhSalaryNianTimeList($where = null) {
		$sql = "select st.*,c.company_name from OA_salarytime_other st,OA_company c where  st.companyId=c.id  and salaryType=" . SALARY_TIME_TYPE;
		if ($where != null) {
			if ($where ['companyName'] != "") {
				$sql .= " and c.company_name='{$where['companyName']}' ";
			} elseif ($where ['salaryTime'] != "") {
				$sql .= " and st.salaryTime='{$where['salaryTime']}' ";
			} elseif ($where ['op_salaryTime'] != "") {
				$sql .= " and st.op_salaryTime='{$where['op_salaryTime']}' ";
			}
		}
		$sql .= " order by op_salaryTime desc ";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	
	// 年终奖计算分页功能
	function searhSalaryNianTimeListCount($where = null) {
        $id = $_SESSION ['admin'] ['id'];
		$sql = "select count(*) as cnt   from OA_salarytime_other st,OA_company c ,OA_admin_company a where  st.companyId=c.id and a.companyId = c.id  and  a.adminId = $id and salaryType=" . SALARY_TIME_TYPE;
		if ($where != null) {
			if ($where ['companyName'] != "") {
				$sql .= " and c.company_name like '%{$where['companyName']}%' ";
			}
			if ($where ['salaryTime'] != "") {
				$sql .= " and st.salaryTime like '%{$where['salaryTime']}%'";
			}
			if ($where ['op_salaryTime'] != "") {
				$sql .= " and st.op_salaryTime>='{$where['op_time']}' and st.op_salaryTime<'{$where['op_salaryTime']}' ";
			}
		}
		$result = $this->g_db_query ( $sql );
		if (! $result) {
			return 0;
		}
		$row = mysql_fetch_assoc ( $result );
		return $row ['cnt'];
	}
	function searhSalaryNianTimeListPage($start = NULL, $limit = NULL, $sort = NULL, $where = null) {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "select st.*,c.company_name from OA_salarytime_other st,OA_company c  ,OA_admin_company a where  st.companyId=c.id and a.companyId = c.id  and  a.adminId = $id and salaryType=" . SALARY_TIME_TYPE;
		if ($where != null) {
			if ($where ['companyName'] != "") {
				$sql .= " and c.company_name like '%{$where['companyName']}%' ";
			}
			if ($where ['salaryTime'] != "") {
				$sql .= " and st.salaryTime like '%{$where['salaryTime']}%'  ";
			}
			if ($where ['op_salaryTime'] != "") {
				$sql .= " and st.op_salaryTime>='{$where['op_time']}' and st.op_salaryTime<'{$where['op_salaryTime']}' ";
			}
		}
		if ($sort) {
			$sql .= " order by $sort";
		}
		if ($start >= 0 && $limit) {
			$sql .= " limit $start,$limit";
		}
		// $sql.=" order by op_salaryTime desc ";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	// *** 年终奖计算以上结束
	function searhSalaryErTimeList($where = null) {
		$sql = "select st.*,c.company_name from OA_salarytime_other st,OA_company c where  st.companyId=c.id  and salaryType=" . ER_SALARY_TIME_TYPE;
		if ($where != null) {
			if ($where ['companyName'] != "") {
				$sql .= " and c.company_name='{$where['companyName']}' ";
			} elseif ($where ['salaryTime'] != "") {
				$sql .= " and st.salaryTime='{$where['salaryTime']}' ";
			} elseif ($where ['op_salaryTime'] != "") {
				$sql .= " and st.op_salaryTime='{$where['op_salaryTime']}' ";
			}
		}
		$sql .= " order by op_salaryTime desc ";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	function searchSalaryListBy_SalaryTimeId($sid) {
		/**
		 * `employid` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
		 * `salaryTimeId` int(11) NOT NULL,
		 * `per_yingfaheji` double(10,2) DEFAULT NULL,
		 * `per_shiye` double(10,2) DEFAULT NULL,
		 * `per_yiliao` double(10,2) DEFAULT NULL,
		 * `per_yanglao` double(10,2) DEFAULT NULL,
		 * `per_gongjijin` double(10,2) DEFAULT NULL,
		 * `per_daikoushui` double(10,2) DEFAULT NULL,
		 * `per_koukuangheji` double(10,2) DEFAULT NULL,
		 * `per_shifaheji` double(10,2) DEFAULT NULL,
		 * `com_shiye` double(10,2) DEFAULT NULL,
		 * `com_yiliao` double(10,2) DEFAULT NULL,
		 * `com_yanglao` double(10,2) DEFAULT NULL,
		 * `com_gongshang` double(10,2) DEFAULT NULL,
		 * `com_shengyu` double(10,2) DEFAULT NULL,
		 * `com_gongjijin` double(10,2) DEFAULT NULL,
		 * `com_heji` double(10,2) DEFAULT NULL,
		 * `laowufei` double(10,2) DEFAULT NULL,
		 * `canbaojin` double(10,2) DEFAULT NULL,
		 * `danganfei` double(10,2) DEFAULT NULL,
		 * `paysum_zhongqi` double(10,2) DEFAULT NULL,
		 * `salary_type` int(2) NOT NULL DEFAULT '0',
		 */
		$sql = "select *  from OA_salary where salaryTimeId=$sid";
		$list = $this->g_db_query ( $sql );
		return $list;
	}

    function searchSalaryListBy_SalaryTimeId_New($sid) {
        $sql = "SELECT c.*,b.* FROM (select *  from OA_salary where salaryTimeId=$sid)  b,
(select salaryId,
                MAX(CASE WHEN a.fieldName = '部门' THEN a.fieldValue ELSE NULL END)  as 部门,
                MAX(CASE WHEN a.fieldName = '姓名' THEN a.fieldValue ELSE NULL END)  as 姓名,
								MAX(CASE WHEN a.fieldName = '身份证号' THEN a.fieldValue ELSE NULL END)  as 身份证号,
								MAX(CASE WHEN a.fieldName = '基本工资' THEN a.fieldValue ELSE NULL END)  as 基本工资,
								MAX(CASE WHEN a.fieldName = '职务工资' THEN a.fieldValue ELSE NULL END)  as 职务工资,
								MAX(CASE WHEN a.fieldName = '年度骨干津贴' THEN a.fieldValue ELSE NULL END)  as 年度骨干津贴,
								MAX(CASE WHEN a.fieldName = '季度骨干津贴' THEN a.fieldValue ELSE NULL END)  as 季度骨干津贴,
								MAX(CASE WHEN a.fieldName = '月骨干津贴' THEN a.fieldValue ELSE NULL END)  as 月骨干津贴,
								MAX(CASE WHEN a.fieldName = '保密津贴' THEN a.fieldValue ELSE NULL END)  as 保密津贴,
								MAX(CASE WHEN a.fieldName = '补发工资' THEN a.fieldValue ELSE NULL END)  as 补发工资,
								MAX(CASE WHEN a.fieldName = '交通补贴' THEN a.fieldValue ELSE NULL END)  as 交通补贴,
								MAX(CASE WHEN a.fieldName = '季度奖' THEN a.fieldValue ELSE NULL END)  as 季度奖,
								MAX(CASE WHEN a.fieldName = '质量奖' THEN a.fieldValue ELSE NULL END)  as 质量奖,
								MAX(CASE WHEN a.fieldName = '考核工资' THEN a.fieldValue ELSE NULL END)  as 考核工资,
								MAX(CASE WHEN a.fieldName like '%银行卡号%'   THEN a.fieldValue ELSE NULL END)  as 银行卡号,
								MAX(CASE WHEN a.fieldName = '身份类别' THEN a.fieldValue ELSE NULL END)  as 身份类别,
								MAX(CASE WHEN a.fieldName  like '%社保基数%'  THEN a.fieldValue ELSE NULL END)  as 社保基数,
								MAX(CASE WHEN a.fieldName   like '%公积金基数%' THEN a.fieldValue ELSE NULL END)  as 公积金基数
                FROM OA_salarymovement as a
                WHERE salaryId in  (select id  from OA_salary where salaryTimeId=$sid)   group by salaryId
) c  WHERE b.id = c.salaryId";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
	// 查询年终奖
	function searchNianSalaryListBy_SalaryTimeId($sid) {
		$sql = "select *  from OA_nian_salary where salaryTimeId=$sid";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	// 查询个人年终奖
	function searchNianSalaryListBy_SalaryTimeIdAndPersonNo($sid, $employNo) {
		$sql = "select *  from OA_nian_salary where salaryTimeId=$sid and employid='{$employNo}'";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	function searchNianSalaryTimeBySalaryTimeAndComId($sTime, $companyId) {
		$sql = "select *  from OA_salarytime_other where salaryTime='$sTime' and salaryType=" . SALARY_TIME_TYPE . " and companyId=$companyId ";
		$list = $this->g_db_query ( $sql );
		return mysql_fetch_array ( $list );
	}
	function searchErSalaryTimeBySalaryTimeAndComId($sTime, $companyId) {
		$sql = "select *  from OA_salarytime_other where salaryTime='$sTime' and salaryType=" . ER_SALARY_TIME_TYPE . " and companyId=$companyId ";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	function searchNianSalaryAndErSalaryTimeByComId($companyId) {
		$sql = "select *  from OA_salarytime_other where   companyId=$companyId ";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	// 查询er
	function searchErSalaryListBy_SalaryTimeId($sid) {
		$sql = "select *  from OA_er_salary where salaryTimeId=$sid";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	function searchErSalaryListBy_SalaryTimeIdAndPersonId($sid, $personId) {
		$sql = "select *  from OA_er_salary where salaryTimeId=$sid  and employid='$personId'";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	// 查询员工二次工资合计项
	function searchErSalHejiByPersonIdAndSalTimeErAndComId($comId, $salTime, $personId) {
		$sql = "select  sum(OA_er_salary.ercigongziheji) as erSum ,OA_er_salary.employId  from OA_salarytime_other,OA_er_salary 
    	where  OA_salarytime_other.salarytime='$salTime' and OA_salarytime_other.companyId=$comId
and OA_salarytime_other.id=OA_er_salary.salarytimeId and OA_er_salary.employId='$personId' group by OA_er_salary.employId;";
		$result = $this->g_db_query ( $sql );
		return mysql_fetch_array ( $result );
	}
	function searchErBuKouShuaiHejiByPersonIdAndSalTimeErAndComId($comId, $salTime, $personId) {
		$sql = "select  sum(OA_er_salary.bukoushui) as erSum ,OA_er_salary.employId  from OA_salarytime_other,OA_er_salary 
    	where  OA_salarytime_other.salarytime='$salTime' and OA_salarytime_other.companyId=$comId
and OA_salarytime_other.id=OA_er_salary.salarytimeId and OA_er_salary.employId='$personId' group by OA_er_salary.employId;";
		$result = $this->g_db_query ( $sql );
		return mysql_fetch_array ( $result );
	}
	// 查询员工个人工资根据员工相应字段
	function searchSalaryListBy_SalaryEmpId($emp) {
		$sql = "select st.*,st.id  as stId,e.* ,e.id as  eId,s.*,s.id as sId  from OA_salarytime st,OA_employ e,OA_salary s
    	      where  st.id=s.salaryTimeId  and e.e_num=s.employid and s.employid='{$emp['eno']}' ";
		if (! empty ( $emp ['sTime'] )) {
			
			$sql .= " and st.salaryTime='{$emp['sTime']}'";
		}
		$result = $this->g_db_query ( $sql );
		return $result;
	}
	// 根据员工身份证号 和工资日期ID查询工资
	function searchSalaryListBy_Id($emp) {
		$sql = "select st.*,st.id as stId,e.e_name ,s.*,s.id as sId  from OA_salarytime st,OA_employ e,OA_salary s
    	      where  st.id=s.salaryTimeId  and e.e_num=s.employid and s.employid='{$emp['eno']}' and st.id={$emp['stId']}";
		$result = $this->g_db_query ( $sql );
		return mysql_fetch_array ( $result );
	}
	// 根据工资Id修改工资
	function updateSalBy_sId($salary) {
		$sql = " update OA_salary set per_yingfaheji={$salary['per_yingfaheji']},per_shiye={$salary['per_shiye']},per_yiliao={$salary['per_yiliao']},
    	                            per_yanglao={$salary['per_yanglao']},per_gongjijin={$salary['per_gongjijin']},per_daikoushui={$salary['per_daikoushui']},
    	                            per_koukuangheji={$salary['per_koukuangheji']},per_shifaheji={$salary['per_shifaheji']},com_shiye={$salary['com_shiye']},
    	                            com_yiliao={$salary['com_yiliao']},com_yanglao={$salary['com_yanglao']},com_gongshang={$salary['com_gongshang']},
    	                            com_shengyu={$salary['com_shengyu']},com_gongjijin={$salary['com_gongjijin']},com_heji={$salary['com_heji']},
    	                            laowufei={$salary['laowufei']},canbaojin={$salary['canbaojin']},danganfei={$salary['danganfei']},paysum_zhongqi={$salary['paysum_zhongqi']}
    	                             ,salary_type=1 where id={$salary['sId']}";
		$result = $this->g_db_query ( $sql );
		return $result;
	}
	// 根据工资日期修改工资总数
	function updateTotalBySalaryTimeId($salary) {
		$sql = "update  OA_total  set
	   sum_per_yingfaheji=(select sum(per_yingfaheji) from OA_salary where salaryTimeId={$salary['stId']} ), 
       sum_per_shiye=(select sum(per_shiye) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_per_yiliao=(select sum(per_yiliao) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_per_yanglao=(select sum(per_yanglao) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_per_gongjijin=(select sum(per_gongjijin) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_per_daikoushui=(select sum(per_daikoushui) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_per_koukuangheji=(select sum(per_koukuangheji) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_per_shifaheji=(select sum(per_shifaheji) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_com_shiye=(select sum(com_shiye) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_com_yiliao=(select sum(com_yiliao) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_com_yanglao=(select sum(com_yanglao) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_com_shengyu=(select sum(com_shengyu) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_com_gongjijin=(select sum(com_gongjijin) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_com_heji=(select sum(com_heji) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_laowufei=(select sum(laowufei) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_canbaojin=(select sum(canbaojin) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_danganfei=(select sum(danganfei) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_paysum_zhongqi=(select sum(paysum_zhongqi) from OA_salary where salaryTimeId={$salary['stId']} )
	   where  salaryTime_id ={$salary['stId']};";
		// echo $sql;
		$result = $this->g_db_query ( $sql );
		return $result;
	}
	function searchSumSalaryListBy_SalaryTimeId($sid) {
		$sql = "select *  from OA_total where salaryTime_Id=$sid";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	function searchSumSalaryListBy_ManyCom($where) {
		$sql = "select sum(sum_per_yingfaheji) as sum_per_yingfaheji,
		            sum(sum_per_shiye) as sum_per_shiye,sum(sum_per_yiliao) as sum_per_yiliao
		            ,sum(sum_per_yanglao) as sum_per_yanglao,sum(sum_per_gongjijin) as sum_per_gongjijin,
		            sum(sum_per_daikoushui) as sum_per_daikoushui,sum(sum_per_koukuangheji) as sum_per_koukuangheji,
		            sum(sum_per_shifaheji) as sum_per_shifaheji,sum(sum_com_shiye) as sum_com_shiye
		            ,sum(sum_com_yiliao) as sum_com_yiliao,sum(sum_com_yanglao) as sum_com_yanglao,
		            sum(sum_com_gongshang) as sum_com_gongshang,sum(sum_com_shengyu)  as sum_com_shengyu
		            ,sum(sum_com_gongjijin)as sum_com_gongjijin,sum(sum_com_heji) as sum_com_heji,
    	sum(sum_laowufei) as sum_laowufei,sum(sum_canbaojin)  as sum_canbaojin,
    	sum(sum_danganfei)   as sum_danganfei,sum(sum_paysum_zhongqi)    as sum_paysum_zhongqi from OA_total where salaryTime_Id  in ($where)";
		$list = $this->g_db_query ( $sql );
		return mysql_fetch_array ( $list );
	}
	// 年终奖
	function searchSumNianSalaryListBy_SalaryTimeId($sid) {
		$sql = "select *  from OA_nian_total where salaryTime_Id=$sid";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	//
	function searchSumErSalaryListBy_SalaryTimeId($sid) {
		$sql = "select *  from OA_er_total where salaryTime_Id=$sid";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	function searchSalaryTimeBy_Salarydata($date) {
		$sql = "select *  from OA_salarytime where salaryTime='$date'";
		$result = $this->g_db_query ( $sql );
		return mysql_fetch_array ( $result );
	}
	/**
	 * 查询个税时间
	 * CREATE TABLE `OA_gesui` (
	 * `id` int(11) NOT NULL,
	 * `salaryTimeId` int(11) DEFAULT NULL,
	 * `salTime` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
	 * `geSui_type` int(2) DEFAULT '0',
	 * `opId` int(5) DEFAULT NULL,
	 * `comId` int(11) DEFAULT NULL,
	 * PRIMARY KEY (`id`)
	 * ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
	 *
	 * @param unknown_type $date        	
	 */
	function searchSalaryGeShuiTimeByDateAndComId($date, $comId) {
		$sql = "select *  from OA_gesui where salTime='$date' and comId=$comId ";
		$result = $this->g_db_query ( $sql );
		return mysql_fetch_array ( $result );
	}
	function addSalGeShui($geshuiPO) {
		$sql = "insert into OA_gesui (salaryTimeId,salTime,geSui_type,comId) values
    	     ({$geshuiPO['salaryTimeId']},'{$geshuiPO['salTime']}',{$geshuiPO['geSui_type']},{$geshuiPO['comId']})";
		$result = $this->g_db_query ( $sql );
		return $result;
	}
	function searchSalaryTimeBy_id($date) {
		$sql = "select st.*,c.company_name from OA_salarytime st,OA_company c where  st.companyId=c.id  and st.id=$date";
		$result = $this->g_db_query ( $sql );
		return mysql_fetch_array ( $result );
	}
	// 年终奖
	function searchNianSalaryTimeBy_id($date) {
		$sql = "select st.*,c.company_name from OA_salarytime_other st,OA_company c where  st.companyId=c.id  and st.id=$date";
		$result = $this->g_db_query ( $sql );
		return mysql_fetch_array ( $result );
	}
	function searchSalaryMovementBy_SalaryId($sid) {
		$sql = "select *  from  OA_salarymovement where salaryId=$sid";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	function searchErSalaryMovementBy_SalaryId($sid) {
		$sql = "select *  from  OA_er_movement where ersalaryId=$sid";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	function delSalaryMovement_BySalaryId($timeid) {
		$sql = "delete from  OA_salarymovement where OA_salarymovement.salaryId in (select id from OA_salary where OA_salary.salaryTimeId=$timeid)";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	function delErSalaryMovement_BySalaryId($timeid) {
		$sql = "delete from  OA_er_movement where OA_er_movement.ersalaryId in (select id from OA_er_salary where OA_er_salary.salaryTimeId=$timeid)";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	function delSalaryBy_TimeId($timeid) {
		$sql = "delete from  OA_salary  where salaryTimeId=$timeid";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	function delNianSalaryBy_TimeId($timeid) {
		$sql = "delete from  OA_nian_salary  where salaryTimeId=$timeid";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	function delErSalaryBy_TimeId($timeid) {
		$sql = "delete from  OA_er_salary  where salaryTimeId=$timeid";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	function delSalaryTimeBy_Id($timeid) {
		$sql = "delete from  OA_salarytime  where id=$timeid";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	function delNianSalaryTimeBy_Id($timeid) {
		$sql = "delete from  OA_salarytime_other  where id=$timeid";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	function getSalaryListByComId($comid, $chequeType) {
		// $chequeTypeNum=$chequeType-1;
		$sql = "select *  from  OA_salarytime where companyId=$comid  ";
		$list = $this->g_db_query ( $sql );
		return $list;
	}
	function saveSalaryBill($billArray) {
		/**
		 * `id` int(11) NOT NULL AUTO_INCREMENT,
		 * `salaryTime_id` int(11) NOT NULL,
		 * `bill_type` int(2) NOT NULL,
		 * `bill_date` date DEFAULT NULL,
		 * `bill_item` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
		 * `bill_value` double(10,2) DEFAULT NULL,
		 * `bill_state` int(2) DEFAULT NULL,
		 * `op_id` int(11) DEFAULT NULL,
		 * PRIMARY KEY (`id`)
		 *
		 * @var unknown_type
		 */
		$sql = "insert into OA_bill (salaryTime_id,bill_no,bill_type,bill_date,bill_item,bill_value,bill_state,text) values
    	     ({$billArray['salaryTime_id']},'{$billArray['bill_no']}',{$billArray['bill_type']},'{$billArray['bill_date']}',
    	     '{$billArray['bill_item']}',{$billArray['bill_value']},{$billArray['bill_state']},'{$billArray['text']}')";
		$result = $this->g_db_query ( $sql );
		return $result;
	}
	function updateSalaryTimeState($state, $salaryTimeId) {
		$sql = " update OA_salarytime set salary_state=$state where id=$salaryTimeId";
		$result = $this->g_db_query ( $sql );
		return $result;
	}
	function searchSalaryListByComId($comId, $it = null) {
		$sql = "select OA_salarytime.*,OA_company.company_name  from OA_salarytime,OA_company where OA_company.id=OA_salarytime.companyId and OA_company.id=$comId";
		if ($it == 1) {
			$sql .= " and OA_salarytime.salary_state>0";
		}
		$sql .= " order by  OA_salarytime.salaryTime desc";
		$result = $this->g_db_query ( $sql );
		return $result;
	}
    function searchSalaryListCountByComId($comId, $it = null) {
        $sql = "select count(*) as cnt  from OA_salarytime,OA_company where OA_company.id=OA_salarytime.companyId and OA_company.id=$comId";
        if ($it == 1) {
            $sql .= " and OA_salarytime.salary_state>0";
        }
        $sql .= " order by  OA_salarytime.salaryTime ";
        $result = $this->g_db_query ( $sql );
        if (! $result) {
            return 0;
        }
        $row = mysql_fetch_assoc ( $result );
        return $row ['cnt'];
        return $result;
    }
	function searchCountBill($salaryTimeId, $billType) {
		$sql = "select count(*) as count from OA_bill where salaryTime_id=$salaryTimeId and bill_type=$billType";
		$result = $this->g_db_query ( $sql );
		return mysql_fetch_array ( $result );
	}
	function searchBillBySalaryTimeId($salaryTimeId, $billType = null) {
		$sql = "select *  from OA_bill where salaryTime_id=$salaryTimeId ";
		if ($billType != null) {
			$sql .= " and bill_type=$billType ";
		}
		$result = $this->g_db_query ( $sql );
		return $result;
	}
	function searchBillById($bilId) {
		$sql = "select *  from OA_bill where id=$bilId ";
		$result = $this->g_db_query ( $sql );
		return mysql_fetch_array ( $result );
	}
	function updateBillById($bill) {
		$sql = "update OA_bill  set bill_item='" . $bill ['bill_item'] . "' , bill_value=" . $bill ['bill_value'] . " where id={$bill['id']} ";
		$result = $this->g_db_query ( $sql );
		return $result;
	}
	function delBillById($bill) {
		$sql = "delete from  OA_bill  where id={$bill} ";
		$result = $this->g_db_query ( $sql );
		return $result;
	}
	
	/**
	 * 根据工资月份和身份证查询某员工的应发合计
	 */
	function searchSalBy_EnoAndSalTime($salaryTime, $eno) {
		$sql = "select OA_salarytime.*,OA_salary.*  from OA_salarytime,OA_salary
        where OA_salary.salaryTimeId=OA_salarytime.id  and OA_salarytime.salaryTime='$salaryTime' 
              and OA_salary.employid='$eno' ";
		$result = $this->g_db_query ( $sql );
		return mysql_fetch_array ( $result );
	}
	
	/**
	 * /**
	 * 根据工资月份和身份证查询某员工的应发合计
	 */
	function searchSalBy_EnoAndSalTimeId($salaryTime, $eno) {
		$sql = "select *  from OA_salary
        where salaryTimeId=$salaryTime   and employid='$eno' ";
		$result = $this->g_db_query ( $sql );
		return mysql_fetch_array ( $result );
	}
	
	/**
	 * 根据模糊查询，查询公司名称列表
	 */
	function getCompanyLisyByName($comName) {
		$id = $_SESSION ['admin'] ['id'];
		$sql = "select c.id ,c.company_name  from OA_company c,OA_admin_company a  where  a.companyId = c.id 
		and c.company_name like '%$comName%' and a.adminId = $id";
		$result = $this->g_db_query ( $sql );
		return $result;
	}
	function updateLeijiyue($leiji, $timeId) {
		$sql = "update OA_salarytime set salary_leijiyue=$leiji where id=$timeId ";
		
		$result = $this->g_db_query ( $sql );
		return $result;
	}
	/**
	 * 修改工资的身份证号
	 */
	function updateSalaryEmNoByEmNo($eNo, $yuan) {
		$sql = "update OA_salary set employid='$eNo' where employid='$yuan' ";
		
		$result = $this->g_db_query ( $sql );
		return $result;
	}
	function updateNianSalaryEmNoByEmNo($eNo, $yuan) {
		$sql = "update OA_nian_salary set employid='$eNo' where employid='$yuan' ";
		
		$result = $this->g_db_query ( $sql );
		return $result;
	}
	function updateErSalaryEmNoByEmNo($eNo, $yuan) {
		$sql = "update OA_er_salary set employid='$eNo' where employid='$yuan' ";
		
		$result = $this->g_db_query ( $sql );
		return $result;
	}
	function updateSalTimeMarkBySalTimeId($mark, $timeId) {
		$sql = "update OA_salarytime set mark='$mark' where id=$timeId ";
		
		$result = $this->g_db_query ( $sql );
		return $result;
	}
}
?>

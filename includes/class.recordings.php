<?php

class recordings{

    private $pdo;

    function __construct($pdo){
        $this->pdo = $pdo;
    }

    function get_recordings($campaign_ids,$from_date,$to_date,$status,$phone_code,$phone_number, $page){
        $sql = "
            SELECT
                t1.start_time,
                t1.end_time,
                SEC_TO_TIME(t1.length_in_sec) as call_length,
                t1.location,
                t2.`status`,
                t4.status_name as lead_status,
                t6.status_name as call_status,
                t1.`user`,
                CONCAT(t5.phone_code,t5.phone_number) AS phone_number,
                t2.lead_id
            FROM
                recording_log t1
                JOIN vicidial_list t2 ON t2.lead_id = t1.lead_id
                JOIN vicidial_lists t3 ON t3.list_id = t2.list_id
                JOIN vicidial_statuses t4 ON t4.`status` = t2.`status`
                LEFT JOIN vicidial_log t5 ON t5.uniqueid = t1.vicidial_id
                JOIN vicidial_statuses t6 ON t6.`status` = t5.`status`
            WHERE
                t3.campaign_id IN('".implode($campaign_ids,"','")."')
                AND t1.start_time BETWEEN :from_date AND :to_date
                ".(!empty($status) ? 'AND t5.status = :status' : '')."
                ".(!empty($phone_code) ? 'AND t5.phone_code = :phone_code' : '')."
                ".(!empty($phone_number) ? 'AND t5.phone_number = :phone_number' : '')."
            LIMIT :offset, :limit
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam('from_date',$from_date);
        $stmt->bindParam('to_date',$to_date);
        if($status) $stmt->bindParam('status',$status);
        if($phone_code) $stmt->bindParam('phone_code',$phone_code);
        if($phone_number) $stmt->bindParam('phone_number',$phone_number);

        $offset = $page == 1 ? 0 : ($page - 1) * 100;
        $limit = 100;

        $stmt->bindParam('offset',$offset, PDO::PARAM_INT);
        $stmt->bindParam('limit',$limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function get_statuses(){
        $sql = "
            SELECT status,status_name FROM vicidial_statuses WHERE selectable = 'Y' ORDER BY status_name
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

}
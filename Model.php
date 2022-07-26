<?php

require_once 'Connection.php';

class Model extends Connection
{
	public function pst($query, $arr_data = [], $expect_values = true)
    {
        $pdo = parent::connect();
        $pst = $pdo->prepare($query);
        if ($pst->execute($arr_data)) {
            if ($expect_values)
                $res = $pst->fetchAll();
            else
                $res = true;
        }else {
            $res = false;
        }
        return $res;
    }

	public function update_institutions_data()
    {
        $res = $this->pst("SELECT * FROM mdl_user_info_data");

        if (!empty($res))
        {
            $info = [];

            foreach ($res as $val)
            {
                $info['userid'][] = $val->userid;
                $info['data'][] = $val->data;
            }

            $centinel = true;

            foreach ($info['userid'] as $i => $id)
            {
                $query = "UPDATE mdl_user SET institution = :data WHERE id = :id";

                $res = $this->pst($query, ['data' => $info['data'][$i], 'id' => $id], false);

                if (!$res) { $centinel = false; break; }
            }

            $res = ($centinel) ? 'Todos los campos han sido actualizados exitosamente' : 'hubo un error al actualizar la información';

            return $res;
        }
        else
        {
            return false;
        }
    }
}
<?php

function json($code,$msg,$data=[]){
	if(count($data)==0){
		echo json_encode(['code'=>$code,'msg'=>$msg]);
	}else{
		echo json_encode(['code'=>$code,'msg'=>$msg,'data'=>$data]);
	}
}
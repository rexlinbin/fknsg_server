<?php
/***************************************************************************
 * 
 * Copyright (c) 2010 babeltime.com, Inc. All Rights Reserved
 * $Id: readActiveOpen.script.php 145919 2014-12-13 07:52:12Z MingTian $
 * 
 **************************************************************************/

 /**
 * @file $HeadURL: svn://192.168.1.80:3698/C/tags/card/rpcfw/rpcfw_1-0-41-55/module/active/script/readActiveOpen.script.php $
 * @author $Author: MingTian $(tianming@babeltime.com)
 * @date $Date: 2014-12-13 07:52:12 +0000 (Sat, 13 Dec 2014) $
 * @version $Revision: 145919 $
 * @brief 
 *  
 **/
require_once dirname ( dirname ( dirname ( dirname ( __FILE__ ) ) ) ) . "/lib/ParserUtil.php";
require_once dirname ( dirname ( dirname ( dirname ( __FILE__ ) ) ) ) . "/def/Active.def.php";

$inFileName = 'daytaskopen.csv';
$outFileName = 'ACTIVE_OPEN';

if( isset($argv[1]) &&  $argv[1] == '-h' )
{
	exit("usage: $inFileName $outFileName\n");
}

if ( $argc < 3 )
{
	trigger_error( "Please input enough arguments:inputDir && outputDir!\n" );
}

$inputDir = $argv[1];
$outputDir = $argv[2];

$index = 1;
//对应配置表键名
$arrConfKey = array (
		ActiveDef::ACTIVE_LEVEL 					=> $index++,				// 需要的等级
		ActiveDef::ACTIVE_TASK 						=> $index++,				// 任务ID数组
		ActiveDef::ACTIVE_PRIZE						=> $index++,				// 奖励ID数组
);

$arrKeyV1 = array(
		ActiveDef::ACTIVE_TASK,
		ActiveDef::ACTIVE_PRIZE,
);

$file = fopen("$inputDir/$inFileName", 'r');
echo "read $inputDir/$inFileName\n";

// 略过 前两行
$data = fgetcsv($file);
$data = fgetcsv($file);

$confList = array();
while (TRUE)
{
	$data = fgetcsv($file);
	if (empty($data))
	{
		break;
	}

	$conf = array();
	foreach ($arrConfKey as $key => $index)
	{
		if( in_array($key, $arrKeyV1, true) )
		{
			if (empty($data[$index]))
			{
				$conf[$key] = array();
			}
			else
			{
				$conf[$key] = array2Int(str2array($data[$index]));
			}
		}
		else 
		{
			$conf[$key] = intval($data[$index]);
		}
	}

	$confList[$data[0]] = $conf;
}
fclose($file);

print_r($confList);

//输出文件
$file = fopen("$outputDir/$outFileName", "w");
if ( $file == FALSE )
{
	trigger_error( "$outputDir/$outFileName open failed! exit!\n" );
}
fwrite($file, serialize($confList));
fclose($file);
/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
<?php
/**
 * 分账计划任务执行顺序配置
 */

class IwidePayExecute{
	// 同步现付订单状态脚本
	const CHECK_SYNCHRO_SORT = 1;

	// 同步线下的订房订单
	const EXT_HANDLE_RUN_OFFLINE_SORT = 2;

	// 第一次分账
	const SPLIT_CHECK_SORT = 3;

	// 第二次分账+匹配第一次分账结果
	const TRANSFER_CHECK_SORT = 4;

	// 生成欠款记录
	const DEBT_CREATE_SORT = 5;

	// 按公众号结算汇总
	const AUTORUN_SETTLEMENT_INFO_SORT = 6;

	// 欠款抵扣汇总
	const CLEARS_HANDLE_SORT = 7;

	// 按银行卡号汇总
	const AUTORUN_SUM_INFO_SORT = 8;

	// 预生成前一天退款记录
	const AUTORUN_RUN_REFUND_FINANCIAL_SORT = 9;

	// 预生成每天欠款记录
	const AUTORUN_RUN_DEBT_FINANCIAL_SORT = 10;

	// 预生成每天分账记录
	const AUTORUN_RUN_TRANSFER_FINANCIAL_SORT = 11;
}
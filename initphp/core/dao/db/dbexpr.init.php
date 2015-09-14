<?php
/**
 * 一些特殊的条件语句或更新语句
 * 例如更新的时候 num=num+1这种情况，不需要加单引号等符号
 * @author pwstrick
 *
 */
class dbExpr
{
	/**
	 * Storage for the SQL expression.
	 *
	 * @var string
	 */
	protected $_expression;

	/**
	 * Instantiate an expression, which is just a string stored as
	 * an instance member variable.
	 *
	 * @param string $expression The string containing a SQL expression.
	 */
	public function __construct($expression)
	{
		$this->_expression = (string) $expression;
	}

	/**
	 * @return string The string of the SQL expression stored in this object.
	 */
	public function __toString()
	{
		return $this->_expression;
	}

}
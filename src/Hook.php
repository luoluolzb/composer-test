<?php
/**
 * @description Hook类
 * @author      luoluolzb
 */
namespace luoluolzb;

class Hook
{
	/**
	 * 钩子列表
	 * @var array
	 */
	protected static $hooks = [];

	/**
	 * 批量导入钩子
	 * @param  array $data 钩子列表
	 * @return void
	 */
	public static function import($data)
	{
		foreach ($data as $name => $behaviors) {
			self::register($name, $behaviors);
		}
	}

	/**
	 * 在一个钩子下挂载一个或多个行为
	 * @param  string $name   钩子名称
	 * @param  string $plugin 行为类名
	 * @return void
	*/
	public static function register($name, $behaviors)
	{
		isset(self::$hooks[$name]) || self::$hooks[$name] = [];
		if (is_array($behaviors)) {
			foreach ($behaviors as $behavior) {
				self::_registerOne($name, $behavior);
			}
		} else {
			self::_registerOne($name, $behaviors);
		}
	}

	/**
	 * 在一个钩子下挂载一个行为
	 * @param  string $name   钩子名称
	 * @param  string $plugin 行为类名
	 * @return void
	 */
	protected static function _registerOne($name, $behavior)
	{
		if (!class_exists($behavior)) {
			throw new exception\ClassNotFound("Hook '{$behavior}' 不存在!");
		}
		self::$hooks[$name][] = $behavior;
	}

	/**
	 * 触发一个钩子, 依次执行钩子上的行为
	 * @param  string $name   钩子名称
	 * @param  array  $params 传入参数
	 * @return void
	 */
	public static function trigger($name, $params = [])
	{
		if (isset(self::$hooks[$name])) {
			foreach (self::$hooks[$name] as &$behavior) {
				$action = new $behavior;
				call_user_func_array([$action, 'exec'], $params);
			}
		}
	}
}

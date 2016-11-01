<?php

/** Created By Thunder33345 **/
namespace Thunder33345\WorldEdit\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;

use Thunder33345\WorldEdit\CommandHandler;
use Thunder33345\WorldEdit\Loader;
use Thunder33345\WorldEdit\ObjectGetter;

abstract class BaseCommand extends Command implements PluginIdentifiableCommand
{
	//use ObjectGetter;
	/** @var Loader */
	protected $plugin;

	/**
	 * Constructor
	 *
	 * @param $plugin
	 * @param string $name
	 * @param null|string $description
	 * @param array|\string[] $usageMessage
	 * @param array $aliases
	 */
	public function __construct($plugin, $name, $description, $usageMessage, $aliases)
	{
		parent::__construct($name, $description, $usageMessage, $aliases);
		$this->plugin = $plugin;
	}

	/**
	 * @return Loader
	 */
	public function getPlugin()
	{
		return $this->plugin;
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args)
	{
		return $this->onCommand($sender, $commandLabel, $args);
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 *
	 * @return bool
	 */
	public abstract function onCommand(CommandSender $sender, $commandLabel, array $args);

}
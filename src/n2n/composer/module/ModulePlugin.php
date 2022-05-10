<?php
namespace n2n\composer\module;

use Composer\Plugin\PluginInterface;
use Composer\IO\IOInterface;

class ModulePlugin implements PluginInterface {
	
	public function activate(\Composer\Composer $composer, IOInterface $io) {
		$composer->getInstallationManager()->addInstaller(
				$this->buildInstaller($composer, $io));
	}
	
	public function deactivate(\Composer\Composer $composer, IOInterface $io) {
		$composer->getInstallationManager()->removeInstaller(
				$this->buildInstaller($composer, $io));
	}
	
	public function uninstall(\Composer\Composer $composer, IOInterface $io) {
		
	}
	
	private function buildInstaller(\Composer\Composer $composer, IOInterface $io) {
		if ($this->getMainVersion($composer) == 1) {
			return new ModuleInstallerComposer1($io, $composer);
		}
		
		return new ModuleInstallerComposer2($io, $composer);
	}
	
	private function getMainVersion(\Composer\Composer $composer) {
		$matches = [];
		preg_match('/^\d+/', $composer->getVersion(), $matches);
		
		return $matches[0];
	}
}
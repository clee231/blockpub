<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Peer;
use Carbon\Carbon;
use DB;

class PeerController extends Controller
{
    public function add(Request $request) {
		$hostip = (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
		$hostport = $_SERVER['REMOTE_PORT'];
		$now = Carbon::now();
		$diff = Carbon::now();
		$diff->subHour();
		if (isset($request->ip) && isset($request->port)) {
		// Peer is inserting a peer.
		$indatabase = Peer::where('updated_at', '>=', $diff)->where('ip', $hostip)->where('port', $hostport)->get();
		if ($indatabase->count() > 0) {
			$record = $indatabase->first();
			$record->updated_at = $now;
			$record->save();
			return resonse()->json(['msg' => 'Updated your peer info', 'ip' => $record->ip, 'port' => $record->port]);
		}

		$peer = new Peer();
		$peer->ip = $request->ip;
		$peer->port = $request->port;
		$peer->connection_ip = $hostip;
		$peer->connection_port = $hostport;
		$peer->save();
		return response()->json(['msg' => 'Added your host info', 'ip' => $hostip, 'port' => $hostport]);

		} else {
		// No data provided. Use peer info.
		$indatabase = Peer::where('updated_at', '>=', $diff)->where('ip', $hostip)->get();
		if ($indatabase->count() > 0) {
			$record = $indatabase->first();
			$record->updated_at = $now;
			$record->port = $hostport;
			$record->save();
			return response()->json(['msg' => 'Updated your peer info', 'ip' => $record->ip, 'port' => $record->port]);
		}
		$peer = new Peer();
		$peer->ip = $hostip;
		$peer->port = $hostport;
		$peer->connection_ip = $hostip;
		$peer->connection_port = $hostport;
		$peer->save();
		return response()->json(['msg' => 'Added your host info', 'ip' => $hostip, 'port' => $hostport]);
		}
    }

	public function getPeers() {
		$now = Carbon::now();
		$diff = $now->subHour();
		$data = Peer::where('updated_at','>=', $diff)->get();
		$output = [];
		foreach ($data as $item) {
			$output[] = ['ip' => $item->ip, 'port' => $item->port];
		}
		return response()->json($output);
	}
}

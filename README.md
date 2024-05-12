# ore-v2-beta-log-stats
Fore ORE V2 miners that wants to share their mining stats

Usage : 

First, It's onlys to parse displayed infos of ore-cli vor ore V2. It has no use standlalone.

I start the miner with unbuffer and send every stdout to a log-file. 
<pre>unbuffer ./target/release/ore --rpc <your_devnet_rpc> --keypair ~/.config/solana/id.json mine --threads <nb_treads> --buffer-time <bf> > output-ore-v2.log</pre>
When mining is started like that, it will not display anything but you can still display what's in the log file like that : 
<pre>tail -f output-ore-v2.log</pre>

When your mining session is over, you can try this script simply like that :
<pre>php ore-v2-mining.php</pre>

You will need unbuffer to be installed and, of course, php as well. 

There is no error management, it's as simple as that. It's low brain code, don't bother telling me, I already know it. ^^

If something changes in the miner, this script will need redesign. I'll probably update it but, feel free to customise it as it suits you.

++

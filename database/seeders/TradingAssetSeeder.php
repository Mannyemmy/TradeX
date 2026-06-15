<?php

namespace Database\Seeders;

use App\Models\TradingAsset;
use Illuminate\Database\Seeder;

class TradingAssetSeeder extends Seeder
{
    public function run()
    {
        $assets = [
            // Crypto — CoinGecko external_id
            ['external_id' => 'bitcoin', 'name' => 'Bitcoin', 'symbol' => 'BTC', 'asset_class' => 'crypto', 'data_source' => 'coingecko', 'logo_url' => 'https://assets.coingecko.com/coins/images/1/large/bitcoin.png'],
            ['external_id' => 'ethereum', 'name' => 'Ethereum', 'symbol' => 'ETH', 'asset_class' => 'crypto', 'data_source' => 'coingecko', 'logo_url' => 'https://assets.coingecko.com/coins/images/279/large/ethereum.png'],
            ['external_id' => 'tether', 'name' => 'Tether', 'symbol' => 'USDT', 'asset_class' => 'crypto', 'data_source' => 'coingecko', 'logo_url' => 'https://assets.coingecko.com/coins/images/325/large/Tether.png'],
            ['external_id' => 'binancecoin', 'name' => 'BNB', 'symbol' => 'BNB', 'asset_class' => 'crypto', 'data_source' => 'coingecko', 'logo_url' => 'https://assets.coingecko.com/coins/images/825/large/bnb-icon2_2x.png'],
            ['external_id' => 'solana', 'name' => 'Solana', 'symbol' => 'SOL', 'asset_class' => 'crypto', 'data_source' => 'coingecko', 'logo_url' => 'https://assets.coingecko.com/coins/images/4128/large/solana.png'],
            ['external_id' => 'ripple', 'name' => 'XRP', 'symbol' => 'XRP', 'asset_class' => 'crypto', 'data_source' => 'coingecko', 'logo_url' => 'https://assets.coingecko.com/coins/images/44/large/xrp-symbol-white-128.png'],
            ['external_id' => 'usd-coin', 'name' => 'USD Coin', 'symbol' => 'USDC', 'asset_class' => 'crypto', 'data_source' => 'coingecko', 'logo_url' => 'https://assets.coingecko.com/coins/images/6319/large/usdc.png'],
            ['external_id' => 'cardano', 'name' => 'Cardano', 'symbol' => 'ADA', 'asset_class' => 'crypto', 'data_source' => 'coingecko', 'logo_url' => 'https://assets.coingecko.com/coins/images/975/large/cardano.png'],
            ['external_id' => 'dogecoin', 'name' => 'Dogecoin', 'symbol' => 'DOGE', 'asset_class' => 'crypto', 'data_source' => 'coingecko', 'logo_url' => 'https://assets.coingecko.com/coins/images/5/large/dogecoin.png'],
            ['external_id' => 'tron', 'name' => 'TRON', 'symbol' => 'TRX', 'asset_class' => 'crypto', 'data_source' => 'coingecko', 'logo_url' => 'https://assets.coingecko.com/coins/images/1094/large/tron-logo.png'],
            ['external_id' => 'polkadot', 'name' => 'Polkadot', 'symbol' => 'DOT', 'asset_class' => 'crypto', 'data_source' => 'coingecko', 'logo_url' => 'https://assets.coingecko.com/coins/images/12171/large/polkadot.png'],
            ['external_id' => 'chainlink', 'name' => 'Chainlink', 'symbol' => 'LINK', 'asset_class' => 'crypto', 'data_source' => 'coingecko', 'logo_url' => 'https://assets.coingecko.com/coins/images/877/large/chainlink-new-logo.png'],
            ['external_id' => 'avalanche-2', 'name' => 'Avalanche', 'symbol' => 'AVAX', 'asset_class' => 'crypto', 'data_source' => 'coingecko', 'logo_url' => 'https://assets.coingecko.com/coins/images/12559/large/Avalanche_Circle_RedWhite_Trans.png'],
            ['external_id' => 'matic-network', 'name' => 'Polygon', 'symbol' => 'MATIC', 'asset_class' => 'crypto', 'data_source' => 'coingecko', 'logo_url' => 'https://assets.coingecko.com/coins/images/4713/large/polygon.png'],
            ['external_id' => 'litecoin', 'name' => 'Litecoin', 'symbol' => 'LTC', 'asset_class' => 'crypto', 'data_source' => 'coingecko', 'logo_url' => 'https://assets.coingecko.com/coins/images/2/large/litecoin.png'],

            // Forex — TwelveData
            ['external_id' => 'EUR/USD', 'name' => 'Euro / US Dollar', 'symbol' => 'EUR/USD', 'asset_class' => 'forex', 'data_source' => 'twelvedata', 'price' => 1.0862, 'price_change_pct_24h' => 0.12, 'logo_url' => 'https://hatscripts.github.io/circle-flags/flags/eu.svg'],
            ['external_id' => 'GBP/USD', 'name' => 'British Pound / US Dollar', 'symbol' => 'GBP/USD', 'asset_class' => 'forex', 'data_source' => 'twelvedata', 'price' => 1.2734, 'price_change_pct_24h' => -0.08, 'logo_url' => 'https://hatscripts.github.io/circle-flags/flags/gb.svg'],
            ['external_id' => 'USD/JPY', 'name' => 'US Dollar / Japanese Yen', 'symbol' => 'USD/JPY', 'asset_class' => 'forex', 'data_source' => 'twelvedata', 'price' => 149.52, 'price_change_pct_24h' => 0.21, 'logo_url' => 'https://hatscripts.github.io/circle-flags/flags/us.svg'],
            ['external_id' => 'AUD/USD', 'name' => 'Australian Dollar / US Dollar', 'symbol' => 'AUD/USD', 'asset_class' => 'forex', 'data_source' => 'twelvedata', 'price' => 0.6543, 'price_change_pct_24h' => -0.15, 'logo_url' => 'https://hatscripts.github.io/circle-flags/flags/au.svg'],
            ['external_id' => 'USD/CHF', 'name' => 'US Dollar / Swiss Franc', 'symbol' => 'USD/CHF', 'asset_class' => 'forex', 'data_source' => 'twelvedata', 'price' => 0.8821, 'price_change_pct_24h' => 0.05, 'logo_url' => 'https://hatscripts.github.io/circle-flags/flags/ch.svg'],
            ['external_id' => 'NZD/USD', 'name' => 'New Zealand Dollar / US Dollar', 'symbol' => 'NZD/USD', 'asset_class' => 'forex', 'data_source' => 'twelvedata', 'price' => 0.6128, 'price_change_pct_24h' => -0.22, 'logo_url' => 'https://hatscripts.github.io/circle-flags/flags/nz.svg'],
            ['external_id' => 'USD/CAD', 'name' => 'US Dollar / Canadian Dollar', 'symbol' => 'USD/CAD', 'asset_class' => 'forex', 'data_source' => 'twelvedata', 'price' => 1.3612, 'price_change_pct_24h' => 0.09, 'logo_url' => 'https://hatscripts.github.io/circle-flags/flags/ca.svg'],
            ['external_id' => 'EUR/GBP', 'name' => 'Euro / British Pound', 'symbol' => 'EUR/GBP', 'asset_class' => 'forex', 'data_source' => 'twelvedata', 'price' => 0.8524, 'price_change_pct_24h' => 0.04, 'logo_url' => 'https://hatscripts.github.io/circle-flags/flags/eu.svg'],
            ['external_id' => 'EUR/JPY', 'name' => 'Euro / Japanese Yen', 'symbol' => 'EUR/JPY', 'asset_class' => 'forex', 'data_source' => 'twelvedata', 'price' => 162.38, 'price_change_pct_24h' => 0.18, 'logo_url' => 'https://hatscripts.github.io/circle-flags/flags/eu.svg'],
            ['external_id' => 'GBP/JPY', 'name' => 'British Pound / Japanese Yen', 'symbol' => 'GBP/JPY', 'asset_class' => 'forex', 'data_source' => 'twelvedata', 'price' => 190.32, 'price_change_pct_24h' => 0.14, 'logo_url' => 'https://hatscripts.github.io/circle-flags/flags/gb.svg'],
            ['external_id' => 'XAU/USD', 'name' => 'Gold / US Dollar', 'symbol' => 'XAU/USD', 'asset_class' => 'forex', 'data_source' => 'twelvedata', 'price' => 2178.45, 'price_change_pct_24h' => 0.42, 'logo_url' => 'https://img.icons8.com/color/48/gold-bars.png'],
            ['external_id' => 'XAG/USD', 'name' => 'Silver / US Dollar', 'symbol' => 'XAG/USD', 'asset_class' => 'forex', 'data_source' => 'twelvedata', 'price' => 24.82, 'price_change_pct_24h' => 0.68, 'logo_url' => 'https://img.icons8.com/color/48/silver-bars.png'],

            // Stocks — TwelveData (logos from logo.clearbit.com — free, no API key)
            ['external_id' => 'AAPL', 'name' => 'Apple Inc.', 'symbol' => 'AAPL', 'asset_class' => 'stock', 'data_source' => 'twelvedata', 'price' => 178.72, 'price_change_pct_24h' => 1.24, 'logo_url' => 'https://logo.clearbit.com/apple.com'],
            ['external_id' => 'MSFT', 'name' => 'Microsoft Corporation', 'symbol' => 'MSFT', 'asset_class' => 'stock', 'data_source' => 'twelvedata', 'price' => 415.56, 'price_change_pct_24h' => 0.87, 'logo_url' => 'https://logo.clearbit.com/microsoft.com'],
            ['external_id' => 'GOOGL', 'name' => 'Alphabet Inc.', 'symbol' => 'GOOGL', 'asset_class' => 'stock', 'data_source' => 'twelvedata', 'price' => 153.41, 'price_change_pct_24h' => -0.34, 'logo_url' => 'https://logo.clearbit.com/abc.xyz'],
            ['external_id' => 'AMZN', 'name' => 'Amazon.com Inc.', 'symbol' => 'AMZN', 'asset_class' => 'stock', 'data_source' => 'twelvedata', 'price' => 182.15, 'price_change_pct_24h' => 0.96, 'logo_url' => 'https://logo.clearbit.com/amazon.com'],
            ['external_id' => 'TSLA', 'name' => 'Tesla Inc.', 'symbol' => 'TSLA', 'asset_class' => 'stock', 'data_source' => 'twelvedata', 'price' => 175.34, 'price_change_pct_24h' => -2.15, 'logo_url' => 'https://logo.clearbit.com/tesla.com'],
            ['external_id' => 'NVDA', 'name' => 'NVIDIA Corporation', 'symbol' => 'NVDA', 'asset_class' => 'stock', 'data_source' => 'twelvedata', 'price' => 878.35, 'price_change_pct_24h' => 3.42, 'logo_url' => 'https://logo.clearbit.com/nvidia.com'],
            ['external_id' => 'META', 'name' => 'Meta Platforms Inc.', 'symbol' => 'META', 'asset_class' => 'stock', 'data_source' => 'twelvedata', 'price' => 502.18, 'price_change_pct_24h' => 1.58, 'logo_url' => 'https://logo.clearbit.com/meta.com'],
            ['external_id' => 'NFLX', 'name' => 'Netflix Inc.', 'symbol' => 'NFLX', 'asset_class' => 'stock', 'data_source' => 'twelvedata', 'price' => 628.73, 'price_change_pct_24h' => 0.45, 'logo_url' => 'https://logo.clearbit.com/netflix.com'],
            ['external_id' => 'AMD', 'name' => 'Advanced Micro Devices', 'symbol' => 'AMD', 'asset_class' => 'stock', 'data_source' => 'twelvedata', 'price' => 177.68, 'price_change_pct_24h' => -1.23, 'logo_url' => 'https://logo.clearbit.com/amd.com'],
            ['external_id' => 'INTC', 'name' => 'Intel Corporation', 'symbol' => 'INTC', 'asset_class' => 'stock', 'data_source' => 'twelvedata', 'price' => 42.31, 'price_change_pct_24h' => -0.56, 'logo_url' => 'https://logo.clearbit.com/intel.com'],
            ['external_id' => 'PYPL', 'name' => 'PayPal Holdings', 'symbol' => 'PYPL', 'asset_class' => 'stock', 'data_source' => 'twelvedata', 'price' => 63.28, 'price_change_pct_24h' => 0.72, 'logo_url' => 'https://logo.clearbit.com/paypal.com'],
            ['external_id' => 'DIS', 'name' => 'Walt Disney Co.', 'symbol' => 'DIS', 'asset_class' => 'stock', 'data_source' => 'twelvedata', 'price' => 112.45, 'price_change_pct_24h' => 0.31, 'logo_url' => 'https://logo.clearbit.com/disney.com'],
            ['external_id' => 'BA', 'name' => 'Boeing Company', 'symbol' => 'BA', 'asset_class' => 'stock', 'data_source' => 'twelvedata', 'price' => 188.92, 'price_change_pct_24h' => -0.89, 'logo_url' => 'https://logo.clearbit.com/boeing.com'],
            ['external_id' => 'JPM', 'name' => 'JPMorgan Chase & Co.', 'symbol' => 'JPM', 'asset_class' => 'stock', 'data_source' => 'twelvedata', 'price' => 198.37, 'price_change_pct_24h' => 0.64, 'logo_url' => 'https://logo.clearbit.com/jpmorganchase.com'],

            // ETFs — TwelveData
            ['external_id' => 'SPY', 'name' => 'SPDR S&P 500 ETF', 'symbol' => 'SPY', 'asset_class' => 'etf', 'data_source' => 'twelvedata', 'price' => 518.42, 'price_change_pct_24h' => 0.53, 'logo_url' => 'https://logo.clearbit.com/ssga.com'],
            ['external_id' => 'QQQ', 'name' => 'Invesco QQQ Trust', 'symbol' => 'QQQ', 'asset_class' => 'etf', 'data_source' => 'twelvedata', 'price' => 442.87, 'price_change_pct_24h' => 0.78, 'logo_url' => 'https://logo.clearbit.com/invesco.com'],
            ['external_id' => 'IWM', 'name' => 'iShares Russell 2000 ETF', 'symbol' => 'IWM', 'asset_class' => 'etf', 'data_source' => 'twelvedata', 'price' => 207.56, 'price_change_pct_24h' => -0.42, 'logo_url' => 'https://logo.clearbit.com/ishares.com'],
            ['external_id' => 'VTI', 'name' => 'Vanguard Total Stock Market ETF', 'symbol' => 'VTI', 'asset_class' => 'etf', 'data_source' => 'twelvedata', 'price' => 263.18, 'price_change_pct_24h' => 0.48, 'logo_url' => 'https://logo.clearbit.com/vanguard.com'],
            ['external_id' => 'EEM', 'name' => 'iShares MSCI Emerging Markets ETF', 'symbol' => 'EEM', 'asset_class' => 'etf', 'data_source' => 'twelvedata', 'price' => 42.73, 'price_change_pct_24h' => -0.31, 'logo_url' => 'https://logo.clearbit.com/ishares.com'],
            ['external_id' => 'GLD', 'name' => 'SPDR Gold Shares', 'symbol' => 'GLD', 'asset_class' => 'etf', 'data_source' => 'twelvedata', 'price' => 201.45, 'price_change_pct_24h' => 0.38, 'logo_url' => 'https://logo.clearbit.com/ssga.com'],
            ['external_id' => 'TLT', 'name' => 'iShares 20+ Year Treasury Bond ETF', 'symbol' => 'TLT', 'asset_class' => 'etf', 'data_source' => 'twelvedata', 'price' => 92.34, 'price_change_pct_24h' => 0.15, 'logo_url' => 'https://logo.clearbit.com/ishares.com'],
            ['external_id' => 'XLF', 'name' => 'Financial Select Sector SPDR Fund', 'symbol' => 'XLF', 'asset_class' => 'etf', 'data_source' => 'twelvedata', 'price' => 41.28, 'price_change_pct_24h' => 0.62, 'logo_url' => 'https://logo.clearbit.com/ssga.com'],
            ['external_id' => 'ARKK', 'name' => 'ARK Innovation ETF', 'symbol' => 'ARKK', 'asset_class' => 'etf', 'data_source' => 'twelvedata', 'price' => 48.92, 'price_change_pct_24h' => -1.85, 'logo_url' => 'https://logo.clearbit.com/ark-invest.com'],
            ['external_id' => 'DIA', 'name' => 'SPDR Dow Jones Industrial Average ETF', 'symbol' => 'DIA', 'asset_class' => 'etf', 'data_source' => 'twelvedata', 'price' => 393.67, 'price_change_pct_24h' => 0.35, 'logo_url' => 'https://logo.clearbit.com/ssga.com'],

            // Indices — TwelveData
            ['external_id' => 'SPX', 'name' => 'S&P 500', 'symbol' => 'SPX', 'asset_class' => 'index', 'data_source' => 'twelvedata', 'price' => 5175.27, 'price_change_pct_24h' => 0.56, 'logo_url' => 'https://logo.clearbit.com/spglobal.com'],
            ['external_id' => 'IXIC', 'name' => 'NASDAQ Composite', 'symbol' => 'IXIC', 'asset_class' => 'index', 'data_source' => 'twelvedata', 'price' => 16274.94, 'price_change_pct_24h' => 0.82, 'logo_url' => 'https://logo.clearbit.com/nasdaq.com'],
            ['external_id' => 'DJI', 'name' => 'Dow Jones Industrial Average', 'symbol' => 'DJI', 'asset_class' => 'index', 'data_source' => 'twelvedata', 'price' => 39170.35, 'price_change_pct_24h' => 0.34, 'logo_url' => 'https://logo.clearbit.com/dowjones.com'],
            ['external_id' => 'FTSE', 'name' => 'FTSE 100', 'symbol' => 'FTSE', 'asset_class' => 'index', 'data_source' => 'twelvedata', 'price' => 7722.55, 'price_change_pct_24h' => -0.18, 'logo_url' => 'https://logo.clearbit.com/lseg.com'],
            ['external_id' => 'DAX', 'name' => 'DAX Performance Index', 'symbol' => 'DAX', 'asset_class' => 'index', 'data_source' => 'twelvedata', 'price' => 17932.68, 'price_change_pct_24h' => 0.45, 'logo_url' => 'https://logo.clearbit.com/deutsche-boerse.com'],
            ['external_id' => 'N225', 'name' => 'Nikkei 225', 'symbol' => 'N225', 'asset_class' => 'index', 'data_source' => 'twelvedata', 'price' => 39688.94, 'price_change_pct_24h' => 1.12, 'logo_url' => 'https://logo.clearbit.com/nikkei.com'],
            ['external_id' => 'HSI', 'name' => 'Hang Seng Index', 'symbol' => 'HSI', 'asset_class' => 'index', 'data_source' => 'twelvedata', 'price' => 16529.48, 'price_change_pct_24h' => -0.73, 'logo_url' => 'https://logo.clearbit.com/hsi.com.hk'],
            ['external_id' => 'STOXX50E', 'name' => 'EURO STOXX 50', 'symbol' => 'STOXX50E', 'asset_class' => 'index', 'data_source' => 'twelvedata', 'price' => 4982.76, 'price_change_pct_24h' => 0.28, 'logo_url' => 'https://logo.clearbit.com/stoxx.com'],
        ];

        foreach ($assets as $asset) {
            TradingAsset::updateOrCreate(
                ['external_id' => $asset['external_id'], 'data_source' => $asset['data_source']],
                $asset
            );
        }
    }
}

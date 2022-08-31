<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertSeaportsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //


        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'TJP','Tanjung Priok','JKT','Jakarta','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'SMRG','Semarang','JATENG','Semarang','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'TJGITN','Pelabuhan Tanjung Intan','JATENG','Cilacap','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'BTGL','Pelabuhan Batu Guluk','JATENG','Madura','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'KLAG','Pelabuhan Kalianget','JATIM','Madura','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'KLMS','Pelabuhan Kalimas','JATIM','Surabaya','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'KML','Pelabuhan Kamal','JATIM','Madura','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'KTPG','Pelabuhan Ketapang','JATIM','Banyuwangi','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'TJGPRK','Pelabuhan Tanjung Perak','JATIM','Surabaya','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'UJG','Pelabuhan Ujung','JATIM','Surabaya','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'TJGWGI','Pelabuhan Tanjung Wangi','JATIM','Banyuwangi','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'CRBN','Pelabuhan Cirebon','JATIM','Cirebon','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'PRTW','Pelabuhan Pertiwi','JABAR','Subang','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'PRMK','Pelabuhan Pramuka','JABAR','Garut','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'MRK','Pelabuhan Merak','JABAR','Banten','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'SDKLP','Pelabuhan Sunda Kelapa','BTN','Jakarta','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'JAGOH','Pelabuhan ASDP Jagoh','JKT','Jakarta','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'DMPK','Pelabuhan ASDP Dompak','KPLNRIAU','Lingga','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'PRTRMPK','Pelabuhan ASDP Parit Rempak','KPLNRIAU','Tanjungpinang','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'TJNGUBN','Pelabuhan ASDP Tanjung Uban','JATENG','Karimun','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'TLGPGR','Pelabuhan ASDP Telaga Punggur','KPLNRIAU','Bintan','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'BKG','Pelabuhan Bakong','KPLNRIAU','Batam','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'BTMCT','Pelabuhan Batam Centre','KPLNRIAU','Lingga','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'BTAPR','Pelabuhan Batu Ampar','KPLNRIAU','Batam','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'BLGLG','Pelabuhan Bulang Linggi','KPLNRIAU','Batam','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'DBSGP','Pelabuhan Dabo Singkep','KPLNRIAU','Bintan','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'HRBB','Pelabuhan Harbour Bay','KPLNRIAU','Lingga','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'KJGSB','Pelabuhan Kijang Sri Bayintan','KPLNRIAU','Batam','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'KOTE','Pelabuhan Kote','KPLNRIAU','Bintan','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'LTGJMJ','Pelabuhan Letung Jemaja','KPLNRIAU','Lingga','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'MRKTA','Pelabuhan Marok Tua','KPLNRIAU','Kepulauan Anambas','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'TLPGR','Pelabuhan Telaga Punggur','KPLNRIAU','Lingga','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'TRMP','Pelabuhan Tarempa','KPLNRIAU','Batam','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'TJSS','Pelabuhan Tanjung Setelung Serasan','KPLNRIAU','Kepulauan Anambas','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'TJBTN','Pelabuhan Tanjung Buton','KPLNRIAU','Natuna','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'TJNBK','Pelabuhan Tanjung Balai Karimun','KPLNRIAU','Lingga','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'SGG','Pelabuhan Sunggak','KPLNRIAU','Karimun','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'SGB','Pelabuhan Sungai Buluh','KPLNRIAU','Kepulauan Anambas','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'SRP','Pelabuhan Sri Payung','KPLNRIAU','Lingga','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'SRBP','Pelabuhan Sri Bintan Pura','KPLNRIAU','Tanjungpinang','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'SJTNG','Pelabuhan Sijantung','KPLNRIAU','Tanjungpinang','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'SKPNG','Pelabuhan Sekupang','KPLNRIAU','Batam','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'SNYG','Pelabuhan Senayang','KPLNRIAU','Batam','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'SETM','Pelabuhan Sei Tenam','KPLNRIAU','Lingga','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'TLKBYR','Pelabuhan Teluk Bayur','KPLNRIAU','Lingga','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'MRA','Pelabuhan Muara','SUMBAR','Padang','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'TJGPDN','Pelabuhan Tanjung Pandan','BGKABLTG','Bangka Belitung','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'PKLBLM','Pelabuhan Pangkal Balam','BGKABLTG','Bangka Belitung','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'TJGBL','Pelabuhan Tanjung Balai','SUMUT','Sumatera Utara','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'BLWN','Pelabuhan Belawan','SUMUT','Sumatera Utara','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'YSHSK','Pelabuhan Yoseph Iskandar','ACEH','Aceh Selatan','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'KRGK','Pelabuhan Krueng Geukueh','ACEH','Tapaktuan','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'BJRMN','Banjarmasin','KALSEL','Banjarmasin','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'DWKR','Dwikora','KALBAR','Batu Licin, Satui','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'PLGK','Palangkaraya','KALTENG','Palangkaraya','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'SMYG','Pelabuhan Semayang','KALTIM','Balikpapan','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'MLDG','Pelabuhan Malundung','KALUT','Tarakan','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'TRSKT','Pelabuhan Trisakti','KALUT','Tarakan','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'SMDR','Pelabuhan Samudera','KALSEL','Banjarmasin','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'STNHSD','Pelabuhan Sulathan Hasanuddin','SULSEL','Makassar','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'GRTL','Pelabuhan Gorontalo','SULUT','Gorontalo','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'AGRK','Pelabuhan Anggrek','SULUT','Gorontalo','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'PTE','Pelabuhan Paotere','SULUT','Gorontalo','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'PMTT','Pelabuhan Pamatata','SULSEL','Selayar','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'TJGRGT','Pelabuhan Tanjung Ringgit','SULSEL','Palopo','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'BLP','Pelabuhan Belopa','SULSEL','Belopa','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'MLL','Pelabuhan Malili','SULSEL','Malili','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'PRE','Pelabuhan Pare Pare','SULSEL','Pare-pare','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'BRU','Pelabuhan Barru','SULSEL','Barru','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'PNTLN','Pelabuhan Pantoloan','SULTENG','Palu','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'KNDR','Pelabuhan Kendari','SULTENG','Kendari','Indonesia','ID',NULL,NULL)");
        DB::STATEMENT("INSERT INTO seaports(id,code,name,cityCode,cityName,countryName,countryCode,created_at,updated_at) VALUES (NULL,'BTON','Pelabuhan Buton','SULTENG','Buton','Indonesia','ID',NULL,NULL)");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

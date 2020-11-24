<?php

use App\Models\DvrLineup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDvrChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dvr_channels', function (Blueprint $table) {
            $table->id();
            $table->string('guide_number', 16)->unique();
            $table->string('mapped_channel_number', 16)->nullable();
            $table->timestamps();
        });

        DB::raw('
            CREATE TRIGGER trg_mapped_default BEFORE INSERT ON dvr_channels FOR EACH ROW
                IF NEW.mapped_channel_number IS NULL THEN
                    SET NEW.mapped_channel_number := NEW.guide_number;
                END IF;;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::raw('DROP TRIGGER trg_mapped_default');

        Schema::dropIfExists('dvr_channels');
    }
}

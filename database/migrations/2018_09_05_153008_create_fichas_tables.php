<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFichasTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      Schema::create('states', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name', 70);
        $table->text('description')->nullable();
        $table->timestamps();
        $table->softDeletes();
      });

      Schema::create('municipalities', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name', 70);
        $table->text('description')->nullable();
        $table->unsignedInteger('state_id');
        $table->timestamps();
        $table->softDeletes();

        $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
      });

      Schema::create('parishes', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name', 70);
        $table->text('description')->nullable();
        $table->unsignedInteger('municipality_id');
        $table->timestamps();
        $table->softDeletes();

        $table->foreign('municipality_id')->references('id')->on('municipalities')->onDelete('cascade');
      });

      Schema::create('procedures', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name', 150);
        $table->text('description')->nullable();
        $table->timestamps();
        $table->softDeletes();
      });

      Schema::create('subprocedures', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name', 150);
        $table->text('description')->nullable();
        $table->unsignedInteger('procedure_id');
        $table->timestamps();
        $table->softDeletes();

        $table->foreign('procedure_id')->references('id')->on('procedures')->onDelete('cascade');
      });

      Schema::create('fichas', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name', 70);
        $table->string('last_name', 70);
        $table->string('email', 70)->nullable();
        $table->string('birthdate', 15)->nullable();
        $table->string('num_id', 15);
        $table->string('num_id_c', 15)->nullable();
        $table->string('num_history', 15);
        $table->string('phone', 15)->nullable();
        $table->string('movil', 15)->nullable();
        $table->string('phone_c', 15)->nullable();
        $table->string('doctor', 50);
        $table->string('last_name_c', 50)->nullable();
        $table->string('name_c', 50)->nullable();
        $table->string('relation_c', 50)->nullable();
        $table->text('address');
        $table->text('diagnosis')->nullable();
        // $table->text('observation');
        $table->text('pathology')->nullable();
        $table->boolean('sex');
        $table->unsignedInteger('classification_id');
        $table->unsignedInteger('asic');
        $table->unsignedInteger('intervention_id');
        $table->string('surgical_procedure', 150)->nullable();
        $table->unsignedInteger('parish_id');
        $table->unsignedInteger('weight')->nullable();
        $table->unsignedInteger('parish')->nullable();
        $table->unsignedInteger('fr')->nullable();
        $table->unsignedInteger('imc')->nullable();
        $table->unsignedInteger('pa')->nullable();
        $table->unsignedInteger('size')->nullable();
        $table->unsignedInteger('speciality_id')->nullable();
        $table->unsignedInteger('consulta_pre_anestesia_id')->nullable();
        $table->unsignedInteger('type_patient')->nullable();
        $table->unsignedInteger('cirugia_emergencia')->nullable();
        $table->string('apto', 5)->nullable();
        $table->date('date_insumo')->nullable();
        $table->date('date_check_pre')->nullable();
        $table->date('operation')->nullable();
        $table->date('date_interconsultas')->nullable();
        $table->date('date_valoration_pre')->nullable();
        $table->text('observation_riesgo_anestesico')->nullable();
        $table->text('observation_interconsultas')->nullable();
        $table->text('observation_pre_operatorio')->nullable();
        $table->timestamps();
        $table->softDeletes();

        $table->foreign('parish_id')->references('id')->on('parishes')->onDelete('cascade');
        $table->foreign('consulta_pre_anestesia_id')->references('id')->on('subprocedures')->onDelete('cascade');
      });

      Schema::create('classifications', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name', 70);
        $table->text('description')->nullable();
        $table->timestamps();
        $table->softDeletes();
      });

      Schema::create('interventions', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name', 70);
        $table->text('description')->nullable();
        $table->timestamps();
        $table->softDeletes();
      });

      Schema::create('specialties', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name', 70);
        $table->text('description')->nullable();
        $table->unsignedInteger('intervention_id');
        $table->timestamps();
        $table->softDeletes();

        $table->foreign('intervention_id')->references('id')->on('interventions')->onDelete('cascade');
      });

      Schema::create('type_patients', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name', 70);
        $table->text('description')->nullable();
        $table->timestamps();
        $table->softDeletes();
      });

      Schema::create('insumos', function (Blueprint $table) {
        $table->increments('id');
        $table->string('code', 50)->nullable();
        $table->string('name', 150);
        $table->text('description')->nullable();
        $table->timestamps();
        $table->softDeletes();
      });

      Schema::create('ficha_insumos', function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('insumo_id');
        $table->unsignedInteger('ficha_id');
        $table->string('cant', 25)->nullable();
        $table->string('origen', 100)->nullable();
        $table->text('observation')->nullable();
        $table->unsignedInteger('state')->default(0);
        $table->timestamps();
        $table->softDeletes();

        $table->foreign('insumo_id')->references('id')->on('insumos')->onDelete('cascade');
        $table->foreign('ficha_id')->references('id')->on('fichas')->onDelete('cascade');
      });

      Schema::create('asics', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name', 150);
        $table->text('description')->nullable();
        $table->timestamps();
        $table->softDeletes();
      });

      Schema::create('ficha_procedures', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name', 150);
        $table->boolean('state')->nullable();
        $table->boolean('apply')->nullable();
        $table->unsignedInteger('ficha_id');
        $table->unsignedInteger('procedure_id')->nullable();
        $table->unsignedInteger('subprocedure_id')->nullable();
        $table->timestamps();
        $table->softDeletes();

        $table->foreign('ficha_id')->references('id')->on('fichas')->onDelete('cascade');
        $table->foreign('procedure_id')->references('id')->on('procedures')->onDelete('cascade');
        $table->foreign('subprocedure_id')->references('id')->on('subprocedures')->onDelete('cascade');
      });

      Schema::create('causas', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name', 150);
        $table->text('observation')->nullable();
        $table->timestamps();
        $table->softDeletes();
      });

      Schema::create('histories', function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('state'); // 1-cirugia 2-suspender 3-reprogramar
        $table->string('cirujano', 150)->nullable();
        $table->string('anestesiologo', 150)->nullable();
        $table->string('sala_pabellon', 150)->nullable();
        $table->date('cirugia_date')->nullable();
        $table->unsignedInteger('causa_id')->nullable();
        $table->text('observation')->nullable();
        $table->date('state_date')->nullable();
        // $table->date('reprogramacion_date')->nullable();
        $table->unsignedInteger('ficha_id');
        $table->timestamps();
        $table->softDeletes();

        $table->foreign('ficha_id')->references('id')->on('fichas')->onDelete('cascade');
        $table->foreign('causa_id')->references('id')->on('causas')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('states');
      Schema::dropIfExists('municipalities');
      Schema::dropIfExists('parishes');
      Schema::dropIfExists('fichas');
      Schema::dropIfExists('classifications');
      Schema::dropIfExists('interventions');
      Schema::dropIfExists('specialties');
      Schema::dropIfExists('type_patients');
      Schema::dropIfExists('insumos');
      Schema::dropIfExists('asics');
    }
  }










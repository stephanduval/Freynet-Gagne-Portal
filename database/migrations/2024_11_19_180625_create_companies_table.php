<?php
   // database/migrations/xxxx_xx_xx_xxxxxx_create_companies_table.php

   use Illuminate\Database\Migrations\Migration;
   use Illuminate\Database\Schema\Blueprint;
   use Illuminate\Support\Facades\Schema;

   class CreateCompaniesTable extends Migration
   {
       /**
        * Run the migrations.
        *
        * @return void
        */
       public function up()
       {
           Schema::create('companies', function (Blueprint $table) {
               $table->id();
               $table->string('company_name');
               $table->timestamps();
           });
       }

       /**
        * Reverse the migrations.
        *
        * @return void
        */
       public function down()
       {
           Schema::dropIfExists('companies');
       }
   }
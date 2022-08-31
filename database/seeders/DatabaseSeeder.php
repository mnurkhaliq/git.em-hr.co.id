<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        $this->call(AirportsTableSeeder::class);
        $this->call(BankTableSeeder::class);
        $this->call(BranchHeadTableSeeder::class);
        $this->call(BranchStaffTableSeeder::class);
        $this->call(EducationsTableSeeder::class);
        $this->call(ExitInterviewReasonTableSeeder::class);
        $this->call(JenisTableSeeder::class);
        $this->call(JurusanSmaTableSeeder::class);
        $this->call(KabupatenTableSeeder::class);
        $this->call(KecamatanTableSeeder::class);
        $this->call(KelurahanTableSeeder::class);
        $this->call(KpiModulesTableSeeder::class);
        $this->call(LiburNasionalTableSeeder::class);
        $this->call(MasterCategoryVisitTableSeeder::class);
        $this->call(MasterCutiTypeTableSeeder::class);
        $this->call(MasterVisitTypeTableSeeder::class);
        $this->call(MedicalTypeTableSeeder::class);
        $this->call(OvertimePayrollTypesTableSeeder::class);
        $this->call(PayrollDeductionsTableSeeder::class);
        $this->call(PayrollEarningsTableSeeder::class);
        $this->call(PayrollNpwpTableSeeder::class);
        $this->call(PayrollOthersTableSeeder::class);
        $this->call(PayrollPtkpTableSeeder::class);
        $this->call(ProgramStudiTableSeeder::class);
        $this->call(ProvinsiTableSeeder::class);
        $this->call(RecruitmentApplicationStatusTableSeeder::class);
        $this->call(RecruitmentPhasesTableSeeder::class);
        $this->call(RecruitmentTypeTableSeeder::class);
        $this->call(SeaportsTableSeeder::class);
        $this->call(SekolahTableSeeder::class);
        $this->call(SettingApprovalLevelTableSeeder::class);
        $this->call(StationsTableSeeder::class);
        $this->call(TrainingTransportationTypeTableSeeder::class);
        $this->call(UniversitasTableSeeder::class);
        $this->call(EventSeeder::class);

        Schema::enableForeignKeyConstraints();
    }
}

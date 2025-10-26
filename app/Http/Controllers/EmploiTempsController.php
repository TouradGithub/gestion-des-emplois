<?php
namespace App\Http\Controllers;

use App\Models\Anneescolaire;
use App\Models\Classe;
use App\Models\EmploiHoraire;
use App\Models\EmploiTemps;
use App\Models\Horaire;
use App\Models\Jour;
use App\Models\Subject;
use App\Models\SubjectTeacher;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmploiTempsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $emplois = EmploiTemps::with(['classe', 'subject', 'teacher', 'horairess'])->get();
        return view('admin.emplois.index', compact('emplois'));
    }

    public function getTrimesters(Request $request)
    {
        $classId = $request->class_id;


        if (!$classId) {
            return response()->json(['trimesters' => []]);
        }
        try {
            $classe = Classe::findOrFail($classId);
            $trimesters = $classe->niveau->trimesters;
            return response()->json(['trimesters' => $trimesters]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error loading trimesters', 'trimesters' => []], 500);
        }
    }

    public function create()
    {
        $classes = \App\Models\Classe::all();
        $subjects = \App\Models\Subject::all();
        $teachers = \App\Models\Teacher::all();
        $trimesters = \App\Models\Trimester::all();
//        $annees = \App\Models\AnneeScolaire::all();
        $jours = \App\Models\Jour::all();
        $horaires = \App\Models\Horaire::all();
        $salles = \App\Models\SalleDeClasse::all();
        return view('admin.emplois.create', compact('classes', 'subjects', 'teachers', 'trimesters', 'jours', 'horaires', 'salles'));
//        return view('admin.emplois.create');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|array',
            'subject_id.*' => 'required|exists:subjects,id',
            'teacher_id' => 'required|array',
            'teacher_id.*' => 'required|exists:teachers,id',
            'trimester_id' => 'required|exists:trimesters,id',
            'jour_id' => 'required|array',
            'jour_id.*' => 'required|exists:sct_refjours,id',
            'horaire_id' => 'required|array',
            'horaire_id.*' => 'required|array',
            'horaire_id.*.*' => 'required|exists:sct_ref_horaires,id',
            'salle_de_classe_id' => 'nullable|array',
            'salle_de_classe_id.*' => 'nullable|exists:salle_de_classes,id',
        ], [
            'class_id.required' => 'La classe est obligatoire.',
            'class_id.exists' => 'La classe sélectionnée n\'existe pas.',
            'subject_id.required' => 'Au moins une matière est obligatoire.',
            'subject_id.*.exists' => 'Une des matières sélectionnées n\'existe pas.',
            'teacher_id.required' => 'Au moins un professeur est obligatoire.',
            'teacher_id.*.exists' => 'Un des professeurs sélectionnés n\'existe pas.',
            'trimester_id.required' => 'Le trimestre est obligatoire.',
            'trimester_id.exists' => 'Le trimestre sélectionné n\'existe pas.',
            'jour_id.required' => 'Au moins un jour est obligatoire.',
            'jour_id.*.exists' => 'Un des jours sélectionnés n\'existe pas.',
            'horaire_id.required' => 'Au moins un horaire est obligatoire.',
            'horaire_id.*.*.exists' => 'Un des horaires sélectionnés n\'existe pas.',
        ]);

        $anneeId = Anneescolaire::where('is_active', true)->first()?->id;

        if (!$anneeId) {
            return redirect()->back()->withErrors(['error' => 'Aucune année scolaire active trouvée.']);
        }

        // فاليديشن متقدم للتحقق من التضارب
        $errors = [];

        for ($i = 0; $i < count($request->teacher_id); $i++) {
            $teacherId = $request->teacher_id[$i];
            $subjectId = $request->subject_id[$i];
            $jourId = $request->jour_id[$i];
            $horaireIds = $request->horaire_id[$i]; // مصفوفة من الحصص الزمنية
            $classId = $request->class_id;
            $trimesterId = $request->trimester_id;

            // فحص كل حصة زمنية للتضارب
            foreach ($horaireIds as $horaireId) {
                // التحقق من عدم تكرار الأستاذ في نفس اليوم والوقت مع نفس المادة
                $conflictSameTeacherSubject = EmploiTemps::where('teacher_id', $teacherId)
                    ->where('subject_id', $subjectId)
                    ->where('jour_id', $jourId)
                    ->where('trimester_id', $trimesterId)
                    ->where('annee_id', $anneeId)
                    ->whereHas('horairess', function($query) use ($horaireId) {
                        $query->where('horaire_id', $horaireId);
                    })
                    ->exists();

                if ($conflictSameTeacherSubject) {
                    $teacher = Teacher::find($teacherId);
                    $subject = Subject::find($subjectId);
                    $jour = Jour::find($jourId);
                    $horaire = Horaire::find($horaireId);

                    $errors[] = "Le professeur {$teacher->name} enseigne déjà la matière {$subject->name} le {$jour->libelle_fr} à {$horaire->libelle_fr}.";
                }

                // التحقق من عدم وجود أستاذين يدرسان نفس المادة في نفس القسم والتوقيت
                $conflictSameSubjectTime = EmploiTemps::where('class_id', $classId)
                    ->where('subject_id', $subjectId)
                    ->where('jour_id', $jourId)
                    ->where('trimester_id', $trimesterId)
                    ->where('annee_id', $anneeId)
                    ->where('teacher_id', '!=', $teacherId)
                    ->whereHas('horairess', function($query) use ($horaireId) {
                        $query->where('horaire_id', $horaireId);
                    })
                    ->exists();

                if ($conflictSameSubjectTime) {
                    $subject = Subject::find($subjectId);
                    $classe = Classe::find($classId);
                    $jour = Jour::find($jourId);
                    $horaire = Horaire::find($horaireId);

                    $errors[] = "La matière {$subject->name} est déjà programmée pour la classe {$classe->nom} le {$jour->libelle_fr} à {$horaire->libelle_fr} avec un autre professeur.";
                }

                // التحقق من عدم تكرار الأستاذ في نفس الوقت (حتى لو مادة مختلفة)
                $conflictTeacherTime = EmploiTemps::where('teacher_id', $teacherId)
                    ->where('jour_id', $jourId)
                    ->where('trimester_id', $trimesterId)
                    ->where('annee_id', $anneeId)
                    ->whereHas('horairess', function($query) use ($horaireId) {
                        $query->where('horaire_id', $horaireId);
                    })
                    ->exists();

                if ($conflictTeacherTime) {
                    $teacher = Teacher::find($teacherId);
                    $jour = Jour::find($jourId);
                    $horaire = Horaire::find($horaireId);

                    $errors[] = "Le professeur {$teacher->name} a déjà un cours programmé le {$jour->libelle_fr} à {$horaire->libelle_fr}.";
                }
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->withErrors($errors)->withInput();
        }

        // إنشاء الحصص إذا لم توجد تضارب
        for ($i = 0; $i < count($request->teacher_id); $i++) {
            $emploi = EmploiTemps::create([
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id[$i],
                'teacher_id' => $request->teacher_id[$i],
                'trimester_id' => $request->trimester_id,
                'annee_id' => $anneeId,
                'jour_id' => $request->jour_id[$i],
                'salle_de_classe_id' => $request->salle_de_classe_id[$i] ?? null,
            ]);

            // إضافة جميع الحصص الزمنية المحددة
            foreach ($request->horaire_id[$i] as $horaireId) {
                EmploiHoraire::create([
                    'emploi_temps_id' => $emploi->id,
                    'horaire_id' => $horaireId,
                ]);
            }
        }

        return redirect()->route('web.emplois.index')->with('success', 'Emploi du temps ajouté avec succès.');
    }

    public function show(Request $request)
    {
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 10;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'desc';

        $query = EmploiTemps::with(['classe', 'subject', 'teacher', 'jour', 'horairess', 'trimester', 'annee', 'salle']);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('teacher', fn($q) => $q->where('name', 'like', "%$search%"))
                ->orWhereHas('subject', fn($q) => $q->where('name', 'like', "%$search%"))
                ->orWhereHas('classe', fn($q) => $q->where('name', 'like', "%$search%"));
        }

        $total = $query->count();

        $emplois = $query->orderBy($sort, $order)
            ->skip($offset)
            ->take($limit)
            ->get();

        $rows = [];
        $no = $offset + 1;

        foreach ($emplois as $emploi) {
            // إعادة تعيين المتغير لكل حصة
            $horaires = '';

            $operate = '';
            $operate .= '<a class="btn btn-xs btn-gradient-primary editdata" data-id="' . $emploi->id . '" title="Modifier"><i class="fa fa-edit"></i></a> ';
            $operate .= '<a class="btn btn-xs btn-gradient-danger deletedata" data-id="' . $emploi->id . '" title="Supprimer"><i class="fa fa-trash"></i></a>';

            // تجميع الحصص لهذا الدرس
            foreach ($emploi->horairess as $horaire) {
                $horaires .= '<span class="badge badge-primary me-1">' . ($horaire->libelle_fr ?? $horaire->libelle_ar ?? '-') . '</span>';
            }

            // إذا لم توجد حصص، عرض رسالة
            if (empty($horaires)) {
                $horaires = '<span class="text-muted">-</span>';
            }

            $rows[] = [
                'id' => $emploi->id,
                'no' => $no++,
                'class' => $emploi->classe?->nom ?? '-',
                'subject' => $emploi->subject?->name ?? '-',
                'teacher' => $emploi->teacher?->name ?? '-',
                'jour' => $emploi->jour?->libelle_fr ?? $emploi->jour?->libelle_ar ?? '-',
                'horaire' => $horaires,
                'salle' => $emploi->salle?->name ?? '<span class="text-muted">Non assignée</span>',
                'trimester' => $emploi->trimester?->name ?? '-',
                'operate' => $operate,
            ];
        }

        return response()->json([
            'total' => $total,
            'rows' => $rows,
        ]);
    }


    public function showEmploi($classId)
    {
        $classe = Classe::findOrFail($classId);
        $emplois_temps =EmploiTemps::where('class_id', $classe->id)->get();
        $calendarData = $this->generateDataCalendar(Jour::orderBy('ordre')->get(), $emplois_temps);
//dd($calendarData);

        ob_start();
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->SetAuthor('Emploi du temps');
        $mpdf->SetTitle('Emploi du temps ');
        $mpdf->SetSubject('Emploi du temps');
        $mpdf->SetFont('arial', '', 10);
        $mpdf->SetMargins(0, 0, 5, 0);
        $mpdf->writeHTML(view('admin.sct_emplois_temps.pdf.classe_emplois_pdf', [
            'classe' => $classe,
            'calendarData' => $calendarData,
            'sctHoraires' =>Horaire::get()->sortBy('ordre'),
            'uniqueJours' => Jour::orderBy('ordre')->get(),
            'date_ref' => '',
        ])->render());
        $mpdf->SetHTMLFooter('
                <table>
                    <tr>
                        <td  align="center" >Imprimé le </td>
                        <td >{DATE j-m-Y H:m:s}</td>
                    </tr>
                </table>'
        );
        $mpdf->Output();
        ob_end_flush();

    }


    public function edit(EmploiTemps $emploi)
    {
        $classes = \App\Models\Classe::all();
        $subjects = \App\Models\Subject::all();
        $teachers = \App\Models\Teacher::all();
        $trimesters = \App\Models\Trimester::all();
        $anneescolaires = \App\Models\Anneescolaire::all();
        $jours = \App\Models\Jour::all();
        $horaires = \App\Models\Horaire::all();

        // Load the related horaires for this emploi
        $emploi->load('ref_horaires');
        $selectedHoraires = $emploi->ref_horaires->pluck('id')->toArray();

        return view('admin.emplois.edit', compact('emploi', 'classes', 'subjects', 'teachers', 'trimesters', 'anneescolaires', 'jours', 'horaires', 'selectedHoraires'));
    }

    public function update(Request $request, EmploiTemps $emploi)
    {
        $request->validate([
            'class_id' => 'required',
            'subject_id' => 'required',
            'teacher_id' => 'required',
            'trimester_id' => 'required',
            'annee_id' => 'required',
            'jour_id' => 'required',
            'horaires' => 'required|array',
            'horaires.*' => 'exists:sct_ref_horaires,id',
        ]);

        // Update the basic fields
        $emploi->update($request->except('horaires'));

        // Sync the horaires relationship (this will remove old ones and add new ones)
        $emploi->ref_horaires()->sync($request->horaires);

        return redirect()->route('web.emplois.index')->with('success', 'Emploi du temps modifié avec succès.');
    }

    public function destroy(EmploiTemps $emploi)
    {
        $emploi->delete();
        return redirect()->route('web.emplois.index')->with('success', 'Emploi supprimé.');
    }

    public function generateDataCalendar($weekDays, $emplois)
    {
        $calendarData = [];
        $timeRange = Horaire::get()->sortBy('ordre');
        foreach ($timeRange as $time) {
            $timeText = $time->libelle_fr;
            $calendarData[$timeText] = [];

            foreach ($weekDays as $day) {
                $detail = $emplois->where('jour_id', $day->id)
                    ->whereIn('id',
                        EmploiHoraire::where('horaire_id', $time->id)
                            ->pluck('emploi_temps_id')
                            ->toArray()
                    )->first();

                if ($detail) {
                    $horaire = $detail->getHoraires();
                    $calendarData[$timeText][] = [
                        'matiere' => $detail->subject->name,
                        'rowspan' => abs(Carbon::parse($horaire[1])->diffInMinutes($horaire[0]) / 60) ?? '',
                        'date' => $day->libelle,
                        'id' => $detail->id,
                        'nbr_heure' => $detail->ref_horaires()->count(),
                        'emploi' => $detail,
                    ];
                } else if (!$emplois->where('sct_ref_jour_id', $day->id)
                    ->whereIn('id',
                        EmploiHoraire::query()
                            ->whereIn('horaire_id',
                                Horaire::query()->
                                where('start_time', '<', $time->start_time)
                                    ->where('end_time', '>=', $time->end_time)
                                    ->pluck('id'))
                            ->pluck('emploi_temps_id'))->count()

                ) {

                    $calendarData[$timeText][] = 1;

                } else {
                    $calendarData[$timeText][] = 0;
                }
            }
        }

        return $calendarData;
    }

    public function getTeachers(Request $request)
    {
        // إضافة debugging
        \Log::info('getTeachers called', [
            'class_id' => $request->class_id,
            'trimester_id' => $request->trimester_id,
            'all_params' => $request->all()
        ]);

        $classId = $request->class_id;
        $trimesterId = $request->trimester_id;

        if (!$classId || !$trimesterId) {
            \Log::info('Missing parameters', ['class_id' => $classId, 'trimester_id' => $trimesterId]);
            return response()->json(['data' => []]);
        }

        try {
            $classe = Classe::findOrFail($classId);
            $anneeId = Anneescolaire::where('is_active', true)->first()?->id;

            if (!$anneeId) {
                return response()->json(['error' => 'No active academic year found', 'data' => []], 404);
            }

            // جلب الأساتذة المعينين لهذا القسم والفصل الدراسي والسنة
            $subjectTeachers = \App\Models\SubjectTeacher::where('trimester_id', $trimesterId)
                                                        ->where('annee_id', $anneeId)
                                                        ->where('class_id', $classId)
                                                        ->with(['teacher', 'subject'])
                                                        ->get();

            // تجميع الأساتذة والمواد
            $teachersData = [];

            foreach ($subjectTeachers as $st) {
                if (!$st->teacher) continue;

                $teacherId = $st->teacher->id;

                if (!isset($teachersData[$teacherId])) {
                    $teachersData[$teacherId] = [
                        'id' => $st->teacher->id,
                        'nom' => $st->teacher->name, // استخدام name بدلاً من nom
                        'prenom' => '', // فارغ لأن الاسم كامل في name
                        'full_name' => $st->teacher->name,
                        'subjects' => []
                    ];
                }

                if ($st->subject) {
                    $teachersData[$teacherId]['subjects'][] = [
                        'id' => $st->subject->id,
                        'name' => $st->subject->name,
                        'coefficient' => $st->subject->coefficient ?? 1
                    ];
                }
            }

            // تحويل إلى مصفوفة مفهرسة
            $formattedTeachers = array_values($teachersData);

            return response()->json(['data' => $formattedTeachers]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error loading teachers: ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function getSubjects(Request $request)
    {
        $teacherId = $request->teacher_id;
        $classId = $request->class_id;
        $trimesterId = $request->trimester_id;

        if (!$teacherId) {
            return response()->json(['subjects' => []]);
        }

        try {
            $anneeId = Anneescolaire::where('is_active', true)->first()?->id;

            if (!$anneeId) {
                return response()->json(['error' => 'No active academic year found', 'subjects' => []], 404);
            }

            // جلب المواد من جدول subject_teacher مباشرة
            $subjectTeachers = \App\Models\SubjectTeacher::where('teacher_id', $teacherId)
                                                        ->where('annee_id', $anneeId)
                                                        ->where('trimester_id', $trimesterId)
                                                        ->where('class_id', $classId)
                                                        ->with('subject')
                                                        ->get();

            $subjects = [];
            foreach ($subjectTeachers as $st) {
                if ($st->subject) {
                    $subjects[] = [
                        'id' => $st->subject->id,
                        'name' => $st->subject->name,
                        'coefficient' => $st->subject->coefficient ?? 1,
                        'code' => $st->subject->code
                    ];
                }
            }

            return response()->json(['subjects' => $subjects]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error loading subjects: ' . $e->getMessage(), 'subjects' => []], 500);
        }
    }

    /**
     * جلب الأساتذة الذين يدرسون في قسم معين
     */
    public function getTeachersByDepartment(Request $request)
    {
        $specialiteId = $request->specialite_id;
        $trimesterId = $request->trimester_id;

        if (!$specialiteId) {
            return response()->json(['teachers' => []]);
        }

        try {
            $anneeId = Anneescolaire::where('is_active', true)->first()?->id;

            $classId = $request->class_id;

            $query = Teacher::whereHas('subjects', function ($q) use ($specialiteId, $trimesterId, $anneeId, $classId) {
                $q->where('specialite_id', $specialiteId);
                if ($trimesterId) {
                    $q->whereHas('teachers', function ($subQuery) use ($trimesterId, $anneeId, $classId) {
                        $subQuery->where('trimester_id', $trimesterId);
                        if ($anneeId) {
                            $subQuery->where('annee_id', $anneeId);
                        }
                        if ($classId) {
                            $subQuery->where('class_id', $classId);
                        }
                    });
                }
            });

            $teachers = $query->with(['subjects' => function ($query) use ($specialiteId) {
                $query->where('specialite_id', $specialiteId);
            }])->get();

            $formattedTeachers = $teachers->map(function ($teacher) {
                return [
                    'id' => $teacher->id,
                    'nom' => $teacher->nom,
                    'prenom' => $teacher->prenom,
                    'full_name' => $teacher->nom . ' ' . $teacher->prenom,
                    'email' => $teacher->email,
                    'subjects_count' => $teacher->subjects->count(),
                    'subjects' => $teacher->subjects->map(function ($subject) {
                        return [
                            'id' => $subject->id,
                            'name' => $subject->name,
                            'coefficient' => $subject->coefficient
                        ];
                    })
                ];
            });

            return response()->json(['teachers' => $formattedTeachers]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error loading teachers: ' . $e->getMessage(), 'teachers' => []], 500);
        }
    }



}


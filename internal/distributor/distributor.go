package distributor

type TestFile struct {
	Path  string
	Suite string
}

type WorkerBucket struct {
	WorkerID int
	Tests    []TestFile
}

type Distribution struct {
	Workers []WorkerBucket
}

func RoundRobin(tests []TestFile, workerCount int) Distribution {
	if workerCount <= 0 {
		workerCount = 1
	}

	buckets := make([]WorkerBucket, workerCount)
	for i := range buckets {
		buckets[i] = WorkerBucket{
			WorkerID: i,
			Tests:    []TestFile{},
		}
	}

	for i, test := range tests {
		workerIdx := i % workerCount
		buckets[workerIdx].Tests = append(buckets[workerIdx].Tests, test)
	}

	return Distribution{Workers: buckets}
}

func (d Distribution) TestCount() int {
	count := 0
	for _, w := range d.Workers {
		count += len(w.Tests)
	}
	return count
}

func (d Distribution) WorkerCount() int {
	return len(d.Workers)
}

func (d Distribution) GetWorkerTests(workerID int) []TestFile {
	if workerID < 0 || workerID >= len(d.Workers) {
		return nil
	}
	return d.Workers[workerID].Tests
}

package runner

import (
	"fmt"

	"github.com/alexdempster44/phpunit-parallel/internal/distributor"
)

type Worker struct {
	ID         int
	Tests      []distributor.TestFile
	RunCommand string
	BaseDir    string
}

func NewWorker(id int, tests []distributor.TestFile, runCommand, baseDir string) *Worker {
	return &Worker{
		ID:         id,
		Tests:      tests,
		RunCommand: runCommand,
		BaseDir:    baseDir,
	}
}

func (w *Worker) Run() error {
	fmt.Printf("Worker %d: running %d tests\n", w.ID, len(w.Tests))
	for _, test := range w.Tests {
		fmt.Printf("    [%s] %s\n", test.Suite, test.Path)
	}
	return nil
}

func (w *Worker) TestCount() int {
	return len(w.Tests)
}

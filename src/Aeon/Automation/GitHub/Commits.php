<?php

declare(strict_types=1);

namespace Aeon\Automation\GitHub;

use Aeon\Automation\Project;
use Github\Client;
use Github\ResultPager;

final class Commits
{
    /**
     * @var Commit[]
     */
    private array $commits;

    public function __construct(Commit ...$commits)
    {
        $this->commits = $commits;
    }

    public static function allFrom(Client $client, Project $project, Reference $fromReference, ?Reference $untilReference = null) : self
    {
        $commitsPaginator = new ResultPager($client);
        $commitsData = $commitsPaginator->fetch($client->api('repo')->commits(), 'all', [$project->organization(), $project->name(), ['sha' => $fromReference->sha()]]);

        $foundAll = false;

        $commits = [];
        $totalCommits = 0;

        while ($foundAll === false) {
            foreach ($commitsData as $commitData) {
                $commit = new Commit($commitData);

                if ($untilReference !== null && $commit->id() === $untilReference->sha()) {
                    $foundAll = true;

                    break;
                }

                $commits[] = $commit;
                $totalCommits += 1;
            }

            if ($foundAll) {
                break;
            }

            if ($commitsPaginator->hasNext()) {
                $commitsData = $commitsPaginator->fetchNext();
            } else {
                break;
            }
        }

        return new self(...$commits);
    }

    public function findBySHA(string $SHA) : ?Commit
    {
        foreach ($this->commits as $commit) {
            if ($commit->id() === $SHA) {
                return $commit;
            }
        }

        return null;
    }

    public function count() : int
    {
        return \count($this->commits);
    }

    /**
     * @return Commit[]
     */
    public function all() : array
    {
        return $this->commits;
    }
}
